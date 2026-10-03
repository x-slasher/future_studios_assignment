<?php

declare(strict_types=1);

use App\Enums\TenantRole;
use App\Mail\SetPasswordMail;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

function freshAuth(): void
{
    app('auth')->forgetGuards();
}

beforeEach(function (): void {
    $this->tenant = createTenant('pro');
    $this->user = createTenantUser($this->tenant, TenantRole::Admin);
    $this->user->update(['email' => 'admin@acme.test']);
});

it('logs in and returns a working token', function (): void {
    $response = $this->postJson('api/v1/auth/login', ['email' => 'admin@acme.test', 'password' => 'password'])
        ->assertOk()
        ->assertJsonPath('data.user.email', 'admin@acme.test')
        ->assertJsonPath('data.user.role', 'admin')
        ->assertJsonPath('data.user.tenant.id', $this->tenant->ulid);

    expect($this->user->fresh()->last_login_at)->not->toBeNull();

    $this->withToken($response->json('data.token'))->getJson('api/v1/tenant')->assertOk();
});

it('returns the same 401 for a wrong password, an unknown email, and an inactive user', function (string $email, string $password, bool $deactivate): void {
    if ($deactivate) {
        $this->user->update(['is_active' => false]);
    }

    $this->postJson('api/v1/auth/login', ['email' => $email, 'password' => $password])
        ->assertUnauthorized()
        ->assertExactJson(['error' => [
            'code' => 'INVALID_CREDENTIALS',
            'message' => 'These credentials do not match our records.',
            'details' => [],
        ]]);
})->with([
    'wrong password' => ['admin@acme.test', 'wrong-password', false],
    'unknown email' => ['nobody@acme.test', 'password', false],
    'inactive user' => ['admin@acme.test', 'password', true],
]);

it('returns 403 TENANT_SUSPENDED for a user of a suspended tenant', function (): void {
    $this->tenant->update(['status' => 'suspended', 'suspended_at' => now()]);

    $this->postJson('api/v1/auth/login', ['email' => 'admin@acme.test', 'password' => 'password'])
        ->assertForbidden()
        ->assertJsonPath('error.code', 'TENANT_SUSPENDED');
});

it('logs in a platform admin with no tenant', function (): void {
    User::factory()->platformAdmin()->create(['email' => 'root@platform.test']);

    $this->postJson('api/v1/auth/login', ['email' => 'root@platform.test', 'password' => 'password'])
        ->assertOk()
        ->assertJsonPath('data.user.role', null)
        ->assertJsonPath('data.user.tenant', null);
});

it('logs out by revoking only the current token', function (): void {
    $current = $this->user->createToken('api')->plainTextToken;
    $other = $this->user->createToken('api')->plainTextToken;

    $this->withToken($current)->postJson('api/v1/auth/logout')->assertNoContent();

    expect($this->user->tokens()->count())->toBe(1);
    freshAuth();
    $this->withToken($current)->getJson('api/v1/auth/me')->assertUnauthorized()->assertJsonPath('error.code', 'UNAUTHENTICATED');
    freshAuth();
    $this->withToken($other)->getJson('api/v1/auth/me')->assertOk();
});

it('returns the user with role, tenant summary, and permissions from /auth/me', function (): void {
    $this->withToken($this->user->createToken('api')->plainTextToken)
        ->getJson('api/v1/auth/me')
        ->assertOk()
        ->assertJsonPath('data.id', $this->user->ulid)
        ->assertJsonPath('data.role', 'admin')
        ->assertJsonPath('data.tenant.name', $this->tenant->name)
        ->assertJsonPath('data.permissions', array_map(
            fn ($permission): string => $permission->value,
            TenantRole::Admin->permissions(),
        ));
});

it('lets a platform admin call /auth/me with no tenant', function (): void {
    actingAsPlatformAdmin();

    $this->getJson('api/v1/auth/me')
        ->assertOk()
        ->assertJsonPath('data.role', null)
        ->assertJsonPath('data.tenant', null)
        ->assertJsonPath('data.permissions', []);
});

it('sets a new password with the token from the email', function (): void {
    Mail::fake();

    $this->postJson('api/v1/auth/forgot-password', ['email' => 'admin@acme.test'])->assertStatus(202);

    $token = null;
    Mail::assertQueued(SetPasswordMail::class, function (SetPasswordMail $mail) use (&$token): bool {
        $token = $mail->token;

        return $mail->hasTo('admin@acme.test') && $mail->queue === 'high';
    });

    $this->postJson('api/v1/auth/reset-password', [
        'email' => 'admin@acme.test',
        'token' => $token,
        'password' => 'NewSecret123',
        'password_confirmation' => 'NewSecret123',
    ])->assertOk();

    $this->postJson('api/v1/auth/login', ['email' => 'admin@acme.test', 'password' => 'NewSecret123'])->assertOk();
    $this->postJson('api/v1/auth/login', ['email' => 'admin@acme.test', 'password' => 'password'])->assertUnauthorized();
});

it('rejects an invalid reset token with 422', function (): void {
    $this->postJson('api/v1/auth/reset-password', [
        'email' => 'admin@acme.test',
        'token' => 'not-a-real-token',
        'password' => 'NewSecret123',
        'password_confirmation' => 'NewSecret123',
    ])->assertStatus(422)->assertJsonValidationErrors(['token'], 'error.details');
});

it('returns 202 for an unknown email and sends nothing', function (): void {
    Mail::fake();

    $this->postJson('api/v1/auth/forgot-password', ['email' => 'nobody@acme.test'])->assertStatus(202);

    Mail::assertNothingOutgoing();
});

it('shows the tenant to a member and lets only the owner rename it', function (): void {
    $owner = createTenantUser($this->tenant, TenantRole::Owner);
    $member = createTenantUser($this->tenant, TenantRole::Member);

    $this->actingAs($member, 'sanctum')->getJson('api/v1/tenant')
        ->assertOk()
        ->assertExactJsonStructure(['data' => ['id', 'name', 'status', 'created_at']]);
    $this->actingAs($member, 'sanctum')->patchJson('api/v1/tenant', ['name' => 'Renamed'])->assertForbidden();
    $this->actingAs($this->user, 'sanctum')->patchJson('api/v1/tenant', ['name' => 'Renamed'])->assertForbidden();

    $this->actingAs($owner, 'sanctum')->patchJson('api/v1/tenant', ['name' => 'Renamed'])
        ->assertOk()
        ->assertJsonPath('data.name', 'Renamed');
    expect(Tenant::query()->find($this->tenant->id)->name)->toBe('Renamed');
});

it('rejects a request with an expired token', function (): void {
    $token = $this->user->createToken('api')->plainTextToken;

    $this->travel(8)->days();

    $this->withToken($token)->getJson('api/v1/auth/me')->assertUnauthorized();
});

it('ends every existing session when the password is reset', function (): void {
    Mail::fake();
    $oldToken = $this->user->createToken('api')->plainTextToken;

    $this->postJson('api/v1/auth/forgot-password', ['email' => 'admin@acme.test'])->assertStatus(202);
    $token = null;
    Mail::assertQueued(SetPasswordMail::class, function (SetPasswordMail $mail) use (&$token): bool {
        $token = $mail->token;

        return true;
    });
    $this->postJson('api/v1/auth/reset-password', [
        'email' => 'admin@acme.test', 'token' => $token, 'password' => 'NewSecret123', 'password_confirmation' => 'NewSecret123',
    ])->assertOk();

    expect($this->user->tokens()->count())->toBe(0);
    freshAuth();
    $this->withToken($oldToken)->getJson('api/v1/auth/me')->assertUnauthorized();
});

it('rejects the token of a user who is no longer active', function (): void {
    $token = $this->user->createToken('api')->plainTextToken;
    User::query()->whereKey($this->user->id)->update(['is_active' => false]);

    $this->withToken($token)->getJson('api/v1/auth/me')->assertUnauthorized();
});

it('rejects a password longer than 72 bytes, which bcrypt would silently truncate', function (): void {
    $long = str_repeat('a1', 37);

    $this->postJson('api/v1/auth/register', [
        'company_name' => 'Long Co', 'name' => 'Long', 'email' => 'long@long.test',
        'password' => $long, 'password_confirmation' => $long,
    ])->assertStatus(422)->assertJsonValidationErrors(['password'], 'error.details');
});

it('treats a non-string email as invalid input, not a server error', function (): void {
    $this->postJson('api/v1/auth/login', ['email' => ['a@b.test'], 'password' => 'x'])
        ->assertStatus(422)
        ->assertJsonPath('error.code', 'VALIDATION_FAILED');
});

it('serves no web page or session cookie at the root URL', function (): void {
    $this->get('/')->assertNotFound()->assertCookieMissing(config('session.cookie'));
});
