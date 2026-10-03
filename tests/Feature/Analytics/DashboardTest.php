<?php

declare(strict_types=1);

use App\Enums\CustomerStatus;
use App\Enums\SubscriptionStatus;
use App\Enums\TenantRole;
use App\Jobs\TakeDailyUsageSnapshotsJob;
use App\Models\DailyUsageSnapshot;
use App\Models\Tenant;
use App\Support\Tenancy\TenantContext;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

beforeEach(function (): void {
    $this->travelTo(CarbonImmutable::parse('2026-10-02 12:00:00'));
});

function addSnapshots(Tenant $tenant, int $days): void
{
    app(TenantContext::class)->run($tenant, function () use ($days): void {
        foreach (range($days, 1) as $daysAgo) {
            DailyUsageSnapshot::query()->create([
                'snapshot_date' => CarbonImmutable::today()->subDays($daysAgo),
                'users_count' => 2,
                'customers_count' => 100 - $daysAgo,
            ]);
        }
    });
}

it('returns usage, customer counts by status, and new customers in the last 30 days', function (): void {
    $user = actingAsTenantUser(TenantRole::Member, 'pro');
    $this->travel(-40)->days();
    createCustomers($user->tenant, 2);
    $this->travel(40)->days();
    createCustomers($user->tenant, 3);
    createCustomers($user->tenant, 1, ['status' => CustomerStatus::Inactive]);

    $this->getJson('api/v1/dashboard')
        ->assertOk()
        ->assertJsonPath('data.subscription', [
            'status' => 'active',
            'plan' => 'pro',
            'current_period_end' => '2026-11-02T12:00:00Z',
            'days_left' => 31,
        ])
        ->assertJsonPath('data.usage.users', ['used' => 1, 'limit' => 25, 'over_limit' => false])
        ->assertJsonPath('data.usage.customers', ['used' => 6, 'limit' => 5000, 'over_limit' => false])
        ->assertJsonPath('data.customers', ['total' => 6, 'active' => 5, 'inactive' => 1, 'new_last_30_days' => 4]);
});

it('shows days left of the trial for a trialing tenant', function (): void {
    actingAsTenantUser(TenantRole::Owner, 'starter');
    DB::table('subscriptions')->update(['status' => 'trialing', 'trial_ends_at' => now()->addDays(10), 'current_period_end' => null]);

    $this->getJson('api/v1/dashboard')
        ->assertJsonPath('data.subscription.status', 'trialing')
        ->assertJsonPath('data.subscription.days_left', 10);
});

it('returns a null trend on starter and a filled trend on pro', function (): void {
    $starter = actingAsTenantUser(TenantRole::Owner, 'starter');
    addSnapshots($starter->tenant, 5);
    $this->getJson('api/v1/dashboard')->assertOk()->assertJsonPath('data.trend', null);

    $pro = actingAsTenantUser(TenantRole::Owner, 'pro');
    addSnapshots($pro->tenant, 40);
    $trend = $this->getJson('api/v1/dashboard')->assertOk()->json('data.trend');

    expect($trend)->toHaveCount(29)
        ->and($trend[0])->toBe(['date' => '2026-09-03', 'users' => 2, 'customers' => 71])
        ->and($trend[28]['date'])->toBe('2026-10-01');
});

it('forbids the dashboard to a platform admin', function (): void {
    actingAsPlatformAdmin();

    $this->getJson('api/v1/dashboard')->assertForbidden();
});

it('returns tenant counts, subscriptions by status and plan, MRR, and churn to the admin', function (): void {
    $this->travel(-40)->days();
    createTenant('free');
    $this->travel(40)->days();
    $pro = createTenant('pro', SubscriptionStatus::Active);
    createTenant('starter', SubscriptionStatus::Trialing);
    createTenant('starter', SubscriptionStatus::PastDue)->update(['status' => 'suspended']);
    createTenant('pro', SubscriptionStatus::Expired);

    $subscriptionId = DB::table('subscriptions')->where('tenant_id', $pro->id)->value('id');
    $proPlanId = DB::table('plans')->where('code', 'pro')->value('id');
    foreach (['cancelled' => 1, 'expired' => 5, 'renewed' => 2, 'past_due' => 3] as $type => $daysAgo) {
        DB::table('subscription_events')->insert([
            'tenant_id' => $pro->id, 'subscription_id' => $subscriptionId, 'type' => $type,
            'to_status' => 'active', 'to_plan_id' => $proPlanId, 'occurred_at' => now()->subDays($daysAgo),
        ]);
    }
    DB::table('subscription_events')->insert([
        'tenant_id' => $pro->id, 'subscription_id' => $subscriptionId, 'type' => 'expired',
        'to_status' => 'expired', 'to_plan_id' => $proPlanId, 'occurred_at' => now()->subDays(45),
    ]);

    actingAsPlatformAdmin();

    $this->getJson('api/v1/admin/dashboard')
        ->assertOk()
        ->assertExactJson(['data' => [
            'tenants' => ['total' => 5, 'active' => 4, 'suspended' => 1, 'new_last_30_days' => 4],
            'subscriptions_by_status' => ['trialing' => 1, 'active' => 2, 'past_due' => 1, 'cancelled' => 0, 'expired' => 1],
            'subscriptions_by_plan' => ['free' => 1, 'starter' => 2, 'pro' => 2],
            'mrr_cents' => 4900 + 1900,
            'churned_last_30_days' => 2,
        ]]);
});

it('forbids the admin dashboard to tenant users', function (): void {
    actingAsTenantUser(TenantRole::Owner);

    $this->getJson('api/v1/admin/dashboard')->assertForbidden();
});

it('creates one snapshot row per active tenant and keeps one row when run twice the same day', function (): void {
    $a = createTenant('pro');
    createCustomers($a, 3);
    createTenantUser($a);
    $b = createTenant('free');
    createTenant('starter')->update(['status' => 'suspended']);

    TakeDailyUsageSnapshotsJob::dispatchSync();
    createCustomers($a, 2);
    TakeDailyUsageSnapshotsJob::dispatchSync();

    $rows = DB::table('daily_usage_snapshots')->orderBy('tenant_id')->get();
    expect($rows)->toHaveCount(2)
        ->and($rows[0]->tenant_id)->toBe($a->id)
        ->and($rows[0]->snapshot_date)->toBe('2026-10-02')
        ->and($rows[0]->users_count)->toBe(1)
        ->and($rows[0]->customers_count)->toBe(5)
        ->and($rows[1]->tenant_id)->toBe($b->id)
        ->and($rows[1]->customers_count)->toBe(0);
});
