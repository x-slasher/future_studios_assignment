<?php

declare(strict_types=1);

use App\Enums\TenantRole;
use App\Listeners\SendSetPasswordEmail;
use App\Mail\SetPasswordMail;
use App\Models\User;
use Illuminate\Events\CallQueuedListener;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;

beforeEach(function (): void {
    $this->owner = actingAsTenantUser(TenantRole::Owner, 'pro');
    $this->tenant = $this->owner->tenant;
});

it('creates a user with a role and queues the set-password email', function (): void {
    Mail::fake();

    $response = $this->postJson('api/v1/users', ['name' => 'Karim', 'email' => 'karim@acme.test', 'role' => 'admin'])
        ->assertCreated()
        ->assertJsonPath('data.name', 'Karim')
        ->assertJsonPath('data.role', 'admin')
        ->assertJsonPath('data.is_active', true)
        ->assertJsonPath('data.last_login_at', null);

    expect($response->json('data.id'))->toHaveLength(26)
        ->and($response->getContent())->not->toContain('tenant_id');

    $user = User::query()->where('email', 'karim@acme.test')->sole();
    expect($user->tenant_id)->toBe($this->tenant->id);

    Mail::assertQueued(SetPasswordMail::class, fn (SetPasswordMail $mail): bool => $mail->hasTo('karim@acme.test'));
});

it('sends the set-password email from a queued listener on the high queue', function (): void {
    Queue::fake();

    $this->postJson('api/v1/users', ['name' => 'Karim', 'email' => 'karim@acme.test', 'role' => 'member'])->assertCreated();

    Queue::assertPushedOn('high', CallQueuedListener::class, fn (CallQueuedListener $job): bool => $job->class === SendSetPasswordEmail::class);
});

it('validates the new user', function (): void {
    $this->postJson('api/v1/users', ['email' => 'not-an-email', 'role' => 'superuser'])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'email', 'role'], 'error.details');
});

it('updates name, role, and is_active', function (): void {
    $user = createTenantUser($this->tenant, TenantRole::Member);

    $this->patchJson("api/v1/users/{$user->ulid}", ['name' => 'New Name', 'role' => 'admin', 'is_active' => false])
        ->assertOk()
        ->assertJsonPath('data.name', 'New Name')
        ->assertJsonPath('data.role', 'admin')
        ->assertJsonPath('data.is_active', false);

    $this->getJson("api/v1/users/{$user->ulid}")->assertJsonPath('data.role', 'admin');
});

it('revokes all tokens when a user is deactivated', function (): void {
    $user = createTenantUser($this->tenant, TenantRole::Member);
    $user->createToken('api');
    $user->createToken('api');

    $this->patchJson("api/v1/users/{$user->ulid}", ['is_active' => false])->assertOk();

    expect($user->tokens()->count())->toBe(0);
});

it('soft-deletes a user and revokes their tokens', function (): void {
    $user = createTenantUser($this->tenant, TenantRole::Member);
    $user->createToken('api');

    $this->deleteJson("api/v1/users/{$user->ulid}")->assertNoContent();

    expect(User::withTrashed()->find($user->id)->trashed())->toBeTrue()
        ->and($user->tokens()->count())->toBe(0);
    $this->getJson("api/v1/users/{$user->ulid}")->assertNotFound();
});

it('lets a deleted user email be added again', function (): void {
    $user = createTenantUser($this->tenant, TenantRole::Member);
    $user->update(['email' => 'again@acme.test']);

    $this->postJson('api/v1/users', ['name' => 'Again', 'email' => 'again@acme.test', 'role' => 'member'])->assertStatus(422);

    $this->deleteJson("api/v1/users/{$user->ulid}")->assertNoContent();

    $this->postJson('api/v1/users', ['name' => 'Again', 'email' => 'again@acme.test', 'role' => 'member'])->assertCreated();
});

it('filters the list by role and search, and paginates', function (): void {
    $this->owner->update(['name' => 'Owner Person']);
    createTenantUser($this->tenant, TenantRole::Admin)->update(['name' => 'Alice Admin']);
    createTenantUser($this->tenant, TenantRole::Member)->update(['name' => 'Bob Member']);
    createTenantUser($this->tenant, TenantRole::Member)->update(['name' => 'Alina Member']);

    $this->getJson('api/v1/users?filter[role]=member')
        ->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('data.*.role', ['member', 'member']);

    $this->getJson('api/v1/users?filter[search]=Ali&sort=name')
        ->assertOk()
        ->assertJsonPath('data.*.name', ['Alice Admin', 'Alina Member']);

    $this->getJson('api/v1/users?per_page=2&page=2&sort=name')
        ->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('meta.total', 4)
        ->assertJsonPath('meta.current_page', 2)
        ->assertJsonPath('meta.last_page', 2);
});

it('filters by is_active and escapes LIKE wildcards in search', function (): void {
    createTenantUser($this->tenant, TenantRole::Member)->update(['name' => 'Off', 'is_active' => false]);

    $this->getJson('api/v1/users?filter[is_active]=false')->assertJsonPath('data.*.name', ['Off']);
    $this->getJson('api/v1/users?filter[search]=%25')->assertJsonCount(0, 'data');
});

it('rejects unknown filters, unknown sorts, and per_page over 100', function (string $query): void {
    $this->getJson("api/v1/users?{$query}")->assertStatus(422)->assertJsonPath('error.code', 'VALIDATION_FAILED');
})->with([
    'filter[tenant_id]=1',
    'sort=email',
    'per_page=101',
]);
