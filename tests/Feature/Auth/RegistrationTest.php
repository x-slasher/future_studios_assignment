<?php

declare(strict_types=1);

use App\Events\TenantRegistered;
use App\Listeners\SendWelcomeEmail;
use App\Mail\SetPasswordMail;
use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Events\CallQueuedListener;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;

/** @return array<string, string> */
function registration(array $overrides = []): array
{
    return [
        'company_name' => 'Rahim Traders',
        'name' => 'Rahim Uddin',
        'email' => 'rahim@rahimtraders.test',
        'password' => 'Secret123',
        'password_confirmation' => 'Secret123',
        ...$overrides,
    ];
}

it('creates the tenant, an owner, a trialing starter subscription, and returns a token', function (): void {
    $response = $this->postJson('api/v1/auth/register', registration())
        ->assertCreated()
        ->assertJsonPath('data.user.email', 'rahim@rahimtraders.test')
        ->assertJsonPath('data.user.role', 'owner')
        ->assertJsonPath('data.user.is_active', true)
        ->assertJsonPath('data.tenant.name', 'Rahim Traders')
        ->assertJsonPath('data.tenant.status', 'active')
        ->assertJsonPath('data.subscription.status', 'trialing')
        ->assertJsonPath('data.subscription.plan.code', 'starter')
        ->assertJsonPath('data.subscription.trial_ends_at', now()->addDays(14)->toIso8601ZuluString());

    expect($response->json('data.token'))->toBeString()->not->toBeEmpty()
        ->and($response->json('data.tenant.id'))->toHaveLength(26)
        ->and($response->getContent())->not->toContain('tenant_id');

    $user = User::query()->where('email', 'rahim@rahimtraders.test')->sole();
    expect($user->tenant_id)->not->toBeNull()
        ->and(DB::table('subscription_events')->where('tenant_id', $user->tenant_id)->value('type'))->toBe('created');

    $this->withToken($response->json('data.token'))
        ->getJson('api/v1/auth/me')
        ->assertOk()
        ->assertJsonPath('data.role', 'owner')
        ->assertJsonPath('data.tenant.name', 'Rahim Traders');
});

it('gives the free plan an active subscription with no period', function (): void {
    $this->postJson('api/v1/auth/register', registration(['plan_code' => 'free']))
        ->assertCreated()
        ->assertJsonPath('data.subscription.status', 'active')
        ->assertJsonPath('data.subscription.plan.code', 'free')
        ->assertJsonPath('data.subscription.trial_ends_at', null)
        ->assertJsonPath('data.subscription.current_period_end', null);
});

it('rejects an inactive plan code with 422', function (): void {
    DB::table('plans')->where('code', 'pro')->update(['is_active' => false]);

    $this->postJson('api/v1/auth/register', registration(['plan_code' => 'pro']))
        ->assertStatus(422)
        ->assertJsonPath('error.code', 'VALIDATION_FAILED')
        ->assertJsonValidationErrors(['plan_code'], 'error.details');

    $this->assertDatabaseCount('tenants', 0);
});

it('rejects a duplicate email with 422 and creates nothing', function (): void {
    createTenantUser(createTenant())->update(['email' => 'rahim@rahimtraders.test']);

    $this->postJson('api/v1/auth/register', registration())
        ->assertStatus(422)
        ->assertJsonValidationErrors(['email'], 'error.details');

    $this->assertDatabaseCount('tenants', 1);
});

it('validates required fields and password confirmation', function (): void {
    $this->postJson('api/v1/auth/register', ['password' => 'Secret123', 'password_confirmation' => 'other'])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['company_name', 'name', 'email', 'password'], 'error.details');
});

it('queues the welcome email on the high queue', function (): void {
    Queue::fake();

    $this->postJson('api/v1/auth/register', registration())->assertCreated();

    Queue::assertPushedOn('high', CallQueuedListener::class, fn (CallQueuedListener $job): bool => $job->class === SendWelcomeEmail::class);
});

it('sends the welcome email and no set-password email', function (): void {
    Mail::fake();

    $this->postJson('api/v1/auth/register', registration())->assertCreated();

    Mail::assertSent(WelcomeMail::class, fn (WelcomeMail $mail): bool => $mail->hasTo('rahim@rahimtraders.test')
        && $mail->companyName === 'Rahim Traders');
    Mail::assertNotSent(SetPasswordMail::class);
    Mail::assertNotQueued(SetPasswordMail::class);
});

it('dispatches TenantRegistered once', function (): void {
    Event::fake([TenantRegistered::class]);

    $this->postJson('api/v1/auth/register', registration())->assertCreated();

    Event::assertDispatchedTimes(TenantRegistered::class, 1);
});
