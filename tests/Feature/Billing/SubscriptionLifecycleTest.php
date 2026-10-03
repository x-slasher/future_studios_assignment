<?php

declare(strict_types=1);

use App\Enums\SubscriptionStatus;
use App\Enums\TenantRole;
use App\Jobs\ProcessSubscriptionLifecycleJob;
use App\Models\Tenant;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;

beforeEach(function (): void {
    $this->travelTo(CarbonImmutable::parse('2026-10-02 12:00:00'));
});

function runLifecycle(): void
{
    ProcessSubscriptionLifecycleJob::dispatchSync();
}

function statusOf(Tenant $tenant): string
{
    return DB::table('subscriptions')->where('tenant_id', $tenant->id)->value('status');
}

it('expires a trial once trial_ends_at has passed', function (): void {
    $tenant = createTenant('starter', SubscriptionStatus::Trialing);

    $this->travel(13)->days();
    runLifecycle();
    expect(statusOf($tenant))->toBe('trialing');

    $this->travel(1)->days();
    runLifecycle();
    expect(statusOf($tenant))->toBe('expired');
});

it('moves an active paid subscription to past_due, then expires it after the grace days', function (): void {
    $tenant = createTenant('pro', SubscriptionStatus::Active);

    $this->travelTo(CarbonImmutable::parse('2026-11-02 12:00:00'));
    runLifecycle();
    expect(statusOf($tenant))->toBe('past_due');

    $this->travel(2)->days();
    runLifecycle();
    expect(statusOf($tenant))->toBe('past_due');

    $this->travel(1)->days();
    runLifecycle();
    expect(statusOf($tenant))->toBe('expired');
});

it('expires a cancelled subscription once ends_at has passed', function (): void {
    $tenant = createTenant('pro', SubscriptionStatus::Cancelled);

    $this->travel(9)->days();
    runLifecycle();
    expect(statusOf($tenant))->toBe('cancelled');

    $this->travel(1)->days();
    runLifecycle();
    expect(statusOf($tenant))->toBe('expired');
});

it('never changes a free plan', function (): void {
    $tenant = createTenant('free', SubscriptionStatus::Active);

    $this->travel(2)->years();
    runLifecycle();

    expect(statusOf($tenant))->toBe('active');
});

it('changes nothing when run a second time', function (): void {
    $tenants = [
        createTenant('starter', SubscriptionStatus::Trialing),
        createTenant('pro', SubscriptionStatus::Active),
        createTenant('pro', SubscriptionStatus::Cancelled),
        createTenant('pro', SubscriptionStatus::PastDue),
    ];
    $this->travel(40)->days();

    runLifecycle();
    $statuses = array_map(statusOf(...), $tenants);
    $events = DB::table('subscription_events')->count();

    runLifecycle();

    expect(array_map(statusOf(...), $tenants))->toBe($statuses)
        ->and(DB::table('subscription_events')->count())->toBe($events)
        ->and($statuses)->toBe(['expired', 'expired', 'expired', 'expired']);
});

it('writes one subscription_events row per change', function (): void {
    $tenant = createTenant('starter', SubscriptionStatus::Trialing);
    $this->travel(15)->days();

    runLifecycle();

    $event = DB::table('subscription_events')->where('tenant_id', $tenant->id)->sole();
    expect($event->type)->toBe('expired')->and($event->from_status)->toBe('trialing')->and($event->to_status)->toBe('expired');
});

it('blocks writes but allows reads for an expired tenant', function (): void {
    $tenant = createTenant('starter', SubscriptionStatus::Trialing);
    Sanctum::actingAs(createTenantUser($tenant, TenantRole::Owner));
    $this->travel(15)->days();
    runLifecycle();

    $this->postJson('api/v1/customers', ['name' => 'Late', 'email' => 'late@store.test'])
        ->assertStatus(402)
        ->assertJsonPath('error.code', 'SUBSCRIPTION_INACTIVE');
    $this->getJson('api/v1/customers')->assertOk();
    $this->getJson('api/v1/subscription')->assertOk()->assertJsonPath('data.is_usable', false);

    $this->postJson('api/v1/subscription/renew')->assertOk()->assertJsonPath('data.status', 'active');
    $this->postJson('api/v1/customers', ['name' => 'Late', 'email' => 'late@store.test'])->assertCreated();
});
