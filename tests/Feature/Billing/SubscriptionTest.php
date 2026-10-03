<?php

declare(strict_types=1);

use App\Enums\SubscriptionEventType;
use App\Enums\SubscriptionStatus;
use App\Enums\TenantRole;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;

function ownerOn(string $planCode, SubscriptionStatus $status): User
{
    $owner = createTenantUser(createTenant($planCode, $status));
    Sanctum::actingAs($owner);

    return $owner;
}

/** @return list<string> */
function eventTypesFor(User $user): array
{
    return DB::table('subscription_events')->where('tenant_id', $user->tenant_id)->orderBy('id')->pluck('type')->all();
}

beforeEach(function (): void {
    $this->travelTo(CarbonImmutable::parse('2026-10-02 12:00:00'));
});

it('returns the plan, status, and usage', function (): void {
    actingAsTenantUser(TenantRole::Owner, 'starter');

    $this->getJson('api/v1/subscription')
        ->assertOk()
        ->assertJsonPath('data.status', 'active')
        ->assertJsonPath('data.plan.code', 'starter')
        ->assertJsonPath('data.is_usable', true)
        ->assertJsonPath('data.usage.users', ['used' => 1, 'limit' => 5, 'over_limit' => false])
        ->assertJsonPath('data.usage.customers', ['used' => 0, 'limit' => 500, 'over_limit' => false])
        ->assertJsonMissingPath('data.tenant_id');
});

it('changes between paid plans and keeps status and dates', function (): void {
    $owner = ownerOn('starter', SubscriptionStatus::Trialing);
    $before = $this->getJson('api/v1/subscription')->json('data');

    $this->postJson('api/v1/subscription/change-plan', ['plan_code' => 'pro'])
        ->assertOk()
        ->assertJsonPath('data.plan.code', 'pro')
        ->assertJsonPath('data.status', 'trialing')
        ->assertJsonPath('data.trial_ends_at', $before['trial_ends_at'])
        ->assertJsonPath('data.usage.users.limit', 25);

    expect(eventTypesFor($owner))->toBe([SubscriptionEventType::PlanChanged->value]);
});

it('changes to the free plan, sets active, and clears the period', function (): void {
    ownerOn('pro', SubscriptionStatus::PastDue);

    $this->postJson('api/v1/subscription/change-plan', ['plan_code' => 'free'])
        ->assertOk()
        ->assertJsonPath('data.plan.code', 'free')
        ->assertJsonPath('data.status', 'active')
        ->assertJsonPath('data.current_period_start', null)
        ->assertJsonPath('data.current_period_end', null)
        ->assertJsonPath('data.trial_ends_at', null);
});

it('rejects change-plan from the free plan', function (): void {
    $owner = ownerOn('free', SubscriptionStatus::Active);

    $this->postJson('api/v1/subscription/change-plan', ['plan_code' => 'pro'])
        ->assertStatus(409)
        ->assertJsonPath('error.code', 'INVALID_SUBSCRIPTION_TRANSITION')
        ->assertJsonPath('error.message', 'Use renew to start a paid plan.');

    expect(eventTypesFor($owner))->toBe([]);
});

it('rejects change-plan to the same plan', function (): void {
    ownerOn('pro', SubscriptionStatus::Active);

    $this->postJson('api/v1/subscription/change-plan', ['plan_code' => 'pro'])->assertStatus(409);
});

it('rejects an inactive or unknown plan code with 422', function (): void {
    ownerOn('pro', SubscriptionStatus::Active);
    DB::table('plans')->where('code', 'starter')->update(['is_active' => false]);

    $this->postJson('api/v1/subscription/change-plan', ['plan_code' => 'starter'])
        ->assertStatus(422)
        ->assertJsonPath('error.code', 'VALIDATION_FAILED');
    $this->postJson('api/v1/subscription/change-plan', ['plan_code' => 'nope'])->assertStatus(422);
});

it('renews an expired subscription to active with a one-month period', function (): void {
    $owner = ownerOn('starter', SubscriptionStatus::Expired);

    $this->postJson('api/v1/subscription/renew')
        ->assertOk()
        ->assertJsonPath('data.status', 'active')
        ->assertJsonPath('data.plan.code', 'starter')
        ->assertJsonPath('data.current_period_start', '2026-10-02T12:00:00Z')
        ->assertJsonPath('data.current_period_end', '2026-11-02T12:00:00Z')
        ->assertJsonPath('data.is_usable', true);

    expect(eventTypesFor($owner))->toBe([SubscriptionEventType::Renewed->value]);
});

it('renews from free onto a paid plan', function (): void {
    ownerOn('free', SubscriptionStatus::Active);

    $this->postJson('api/v1/subscription/renew', ['plan_code' => 'pro'])
        ->assertOk()
        ->assertJsonPath('data.status', 'active')
        ->assertJsonPath('data.plan.code', 'pro')
        ->assertJsonPath('data.current_period_end', '2026-11-02T12:00:00Z');
});

it('rejects renew that would leave the tenant on the free plan or renew an already active paid plan', function (): void {
    ownerOn('free', SubscriptionStatus::Active);
    $this->postJson('api/v1/subscription/renew')->assertStatus(409);

    ownerOn('pro', SubscriptionStatus::Active);
    $this->postJson('api/v1/subscription/renew')->assertStatus(409);
});

it('cancels during trial with ends_at = trial_ends_at', function (): void {
    $owner = ownerOn('pro', SubscriptionStatus::Trialing);
    $trialEndsAt = $this->getJson('api/v1/subscription')->json('data.trial_ends_at');

    $this->postJson('api/v1/subscription/cancel')
        ->assertOk()
        ->assertJsonPath('data.status', 'cancelled')
        ->assertJsonPath('data.cancelled_at', '2026-10-02T12:00:00Z')
        ->assertJsonPath('data.ends_at', $trialEndsAt)
        ->assertJsonPath('data.is_usable', true);

    expect(eventTypesFor($owner))->toBe([SubscriptionEventType::Cancelled->value]);
});

it('cancels an active subscription with ends_at = current_period_end', function (): void {
    ownerOn('pro', SubscriptionStatus::Active);
    $periodEnd = $this->getJson('api/v1/subscription')->json('data.current_period_end');

    $this->postJson('api/v1/subscription/cancel')->assertOk()->assertJsonPath('data.ends_at', $periodEnd);
});

it('rejects cancelling a free plan or an already cancelled subscription', function (): void {
    ownerOn('free', SubscriptionStatus::Active);
    $this->postJson('api/v1/subscription/cancel')->assertStatus(409);

    ownerOn('pro', SubscriptionStatus::Cancelled);
    $this->postJson('api/v1/subscription/cancel')->assertStatus(409);
});

it('forbids a member from changing, renewing, or cancelling', function (string $path): void {
    actingAsTenantUser(TenantRole::Member, 'starter');

    $this->postJson($path, ['plan_code' => 'pro'])
        ->assertForbidden()
        ->assertJsonPath('error.code', 'FORBIDDEN');
})->with([
    'api/v1/subscription/change-plan',
    'api/v1/subscription/renew',
    'api/v1/subscription/cancel',
]);

it('lets a member view the subscription', function (): void {
    actingAsTenantUser(TenantRole::Member);

    $this->getJson('api/v1/subscription')->assertOk();
});

it('writes one subscription_events row per operation with from and to values', function (): void {
    $owner = ownerOn('starter', SubscriptionStatus::Trialing);

    $this->postJson('api/v1/subscription/change-plan', ['plan_code' => 'pro'])->assertOk();
    $this->postJson('api/v1/subscription/cancel')->assertOk();
    $this->postJson('api/v1/subscription/renew', ['plan_code' => 'starter'])->assertOk();

    expect(eventTypesFor($owner))->toBe(['plan_changed', 'cancelled', 'renewed']);

    $last = DB::table('subscription_events')->where('tenant_id', $owner->tenant_id)->orderByDesc('id')->first();
    $planIds = DB::table('plans')->pluck('id', 'code');
    expect($last->from_status)->toBe('cancelled')
        ->and($last->to_status)->toBe('active')
        ->and($last->from_plan_id)->toBe($planIds['pro'])
        ->and($last->to_plan_id)->toBe($planIds['starter']);
});
