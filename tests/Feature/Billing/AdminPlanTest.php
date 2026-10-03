<?php

declare(strict_types=1);

use App\Enums\TenantRole;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;

/** @return list<array{key: string, enabled: bool, limit: int|null}> */
function planFeatures(int $maxCustomers = 100): array
{
    return [
        ['key' => 'max_users', 'enabled' => true, 'limit' => 10],
        ['key' => 'max_customers', 'enabled' => true, 'limit' => $maxCustomers],
        ['key' => 'api_rate_per_minute', 'enabled' => true, 'limit' => 300],
        ['key' => 'customer_export', 'enabled' => true, 'limit' => 99],
        ['key' => 'analytics_trends', 'enabled' => false, 'limit' => null],
    ];
}

it('lets an admin create a plan with features', function (): void {
    actingAsPlatformAdmin();

    $this->postJson('api/v1/admin/plans', [
        'code' => 'business',
        'name' => 'Business',
        'price_cents' => 9900,
        'currency' => 'USD',
        'sort_order' => 4,
        'features' => planFeatures(),
    ])
        ->assertCreated()
        ->assertJsonPath('data.code', 'business')
        ->assertJsonPath('data.price_cents', 9900)
        ->assertJsonCount(5, 'data.features')
        ->assertJsonPath('data.features.1', ['key' => 'max_customers', 'enabled' => true, 'limit' => 100])
        ->assertJsonPath('data.features.3', ['key' => 'customer_export', 'enabled' => true, 'limit' => null]);

    $planId = DB::table('plans')->where('code', 'business')->value('id');
    expect(DB::table('plan_features')->where('plan_id', $planId)->count())->toBe(5);
});

it('validates plan input', function (): void {
    actingAsPlatformAdmin();

    $this->postJson('api/v1/admin/plans', [
        'code' => 'pro',
        'name' => 'Duplicate',
        'price_cents' => -1,
        'currency' => 'usd',
        'features' => [['key' => 'unknown_feature', 'enabled' => true]],
    ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['code', 'price_cents', 'currency', 'features.0.key'], 'error.details');
});

it('applies an updated limit to a tenant on that plan on the next request', function (): void {
    $owner = actingAsTenantUser(TenantRole::Owner, 'pro');
    $this->getJson('api/v1/subscription')->assertJsonPath('data.usage.customers.limit', 5000);

    actingAsPlatformAdmin();
    $planUlid = DB::table('plans')->where('code', 'pro')->value('ulid');
    $this->patchJson("api/v1/admin/plans/{$planUlid}", ['features' => planFeatures(maxCustomers: 7)])
        ->assertOk()
        ->assertJsonPath('data.code', 'pro');

    Sanctum::actingAs($owner);
    $this->getJson('api/v1/subscription')->assertJsonPath('data.usage.customers.limit', 7);
});

it('does not allow the plan code to change', function (): void {
    actingAsPlatformAdmin();
    $planUlid = DB::table('plans')->where('code', 'pro')->value('ulid');

    $this->patchJson("api/v1/admin/plans/{$planUlid}", ['code' => 'pro-2'])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['code'], 'error.details');
});

it('hides an inactive plan from GET /plans but shows it to admins', function (): void {
    $this->getJson('api/v1/plans')->assertOk()->assertJsonCount(3, 'data');

    actingAsPlatformAdmin();
    $planUlid = DB::table('plans')->where('code', 'starter')->value('ulid');
    $this->patchJson("api/v1/admin/plans/{$planUlid}", ['is_active' => false])->assertOk();

    $this->getJson('api/v1/plans')
        ->assertOk()
        ->assertJsonCount(2, 'data')
        ->assertJsonPath('data.*.code', ['free', 'pro']);
    $this->getJson('api/v1/admin/plans')->assertOk()->assertJsonCount(3, 'data');
});

it('lists public plans with features in display order', function (): void {
    $this->getJson('api/v1/plans')
        ->assertOk()
        ->assertJsonPath('data.*.code', ['free', 'starter', 'pro'])
        ->assertJsonPath('data.0.features.0', ['key' => 'max_users', 'enabled' => true, 'limit' => 2]);
});

it('forbids tenant users from admin plan routes', function (): void {
    actingAsTenantUser(TenantRole::Owner);

    $this->getJson('api/v1/admin/plans')->assertForbidden()->assertJsonPath('error.code', 'FORBIDDEN');
});

it('rejects numbers that do not fit the INT UNSIGNED columns', function (): void {
    actingAsPlatformAdmin();

    $this->postJson('api/v1/admin/plans', [
        'code' => 'huge', 'name' => 'Huge', 'price_cents' => 4294967296, 'currency' => 'USD',
        'features' => [['key' => 'max_users', 'enabled' => true, 'limit' => 4294967296]],
    ])->assertStatus(422)->assertJsonValidationErrors(['price_cents', 'features.0.limit'], 'error.details');
});
