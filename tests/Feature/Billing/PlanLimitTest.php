<?php

declare(strict_types=1);

use App\Enums\TenantRole;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;

function ownerWithUsers(string $planCode, int $totalUsers): Tenant
{
    $tenant = createTenant($planCode);
    Sanctum::actingAs(createTenantUser($tenant, TenantRole::Owner));

    for ($i = 1; $i < $totalUsers; $i++) {
        createTenantUser($tenant, TenantRole::Member);
    }

    return $tenant;
}

function newUser(string $email = 'next@acme.test'): array
{
    return ['name' => 'Next', 'email' => $email, 'role' => 'member'];
}

it('blocks the 6th user on starter with 403 PLAN_LIMIT_EXCEEDED and details', function (): void {
    ownerWithUsers('starter', 5);

    $this->postJson('api/v1/users', newUser())
        ->assertForbidden()
        ->assertExactJson(['error' => [
            'code' => 'PLAN_LIMIT_EXCEEDED',
            'message' => 'Your plan allows 5 users. Upgrade your plan to add more.',
            'details' => ['feature' => 'max_users', 'limit' => 5, 'current' => 5],
        ]]);
});

it('allows the 5th user on starter', function (): void {
    ownerWithUsers('starter', 4);

    $this->postJson('api/v1/users', newUser())->assertCreated();
});

it('counts inactive users but not soft-deleted users against max_users', function (): void {
    $tenant = ownerWithUsers('starter', 5);
    $members = DB::table('users')->where('tenant_id', $tenant->id)->where('id', '!=', auth()->id())->pluck('ulid');

    $this->patchJson("api/v1/users/{$members[0]}", ['is_active' => false])->assertOk();
    $this->postJson('api/v1/users', newUser())->assertForbidden();

    $this->deleteJson("api/v1/users/{$members[1]}")->assertNoContent();
    $this->postJson('api/v1/users', newUser())->assertCreated();
});

it('never blocks when the limit is NULL (unlimited)', function (): void {
    $proId = DB::table('plans')->where('code', 'pro')->value('id');
    DB::table('plan_features')->where('plan_id', $proId)->where('feature_key', 'max_users')->update(['limit_value' => null]);
    ownerWithUsers('pro', 30);

    $this->postJson('api/v1/users', newUser())->assertCreated();
    $this->getJson('api/v1/subscription')->assertJsonPath('data.usage.users', ['used' => 31, 'limit' => null, 'over_limit' => false]);
});

function newCustomer(string $email = 'next@store.test'): array
{
    return ['name' => 'Next', 'email' => $email];
}

it('blocks the 51st customer on free with 403 PLAN_LIMIT_EXCEEDED', function (): void {
    $owner = actingAsTenantUser(TenantRole::Owner, 'free');
    createCustomers($owner->tenant, 50);

    $this->postJson('api/v1/customers', newCustomer())
        ->assertForbidden()
        ->assertJsonPath('error.code', 'PLAN_LIMIT_EXCEEDED')
        ->assertJsonPath('error.details', ['feature' => 'max_customers', 'limit' => 50, 'current' => 50]);
});

it('does not count soft-deleted customers', function (): void {
    $owner = actingAsTenantUser(TenantRole::Owner, 'free');
    $customers = createCustomers($owner->tenant, 50);

    $this->deleteJson("api/v1/customers/{$customers->first()->ulid}")->assertNoContent();

    $this->postJson('api/v1/customers', newCustomer())->assertCreated();
});

it('allows a downgrade over the limit, shows over_limit, and blocks the next create', function (): void {
    $owner = actingAsTenantUser(TenantRole::Owner, 'pro');
    createCustomers($owner->tenant, 60);

    $this->postJson('api/v1/subscription/change-plan', ['plan_code' => 'free'])
        ->assertOk()
        ->assertJsonPath('data.plan.code', 'free')
        ->assertJsonPath('data.usage.customers', ['used' => 60, 'limit' => 50, 'over_limit' => true]);

    $this->getJson('api/v1/customers')->assertOk()->assertJsonPath('meta.total', 60);
    $this->postJson('api/v1/customers', newCustomer())->assertForbidden()->assertJsonPath('error.code', 'PLAN_LIMIT_EXCEEDED');
});

it('never blocks customers when the limit is NULL', function (): void {
    $proId = DB::table('plans')->where('code', 'pro')->value('id');
    DB::table('plan_features')->where('plan_id', $proId)->where('feature_key', 'max_customers')->update(['limit_value' => null]);
    $owner = actingAsTenantUser(TenantRole::Owner, 'pro');
    createCustomers($owner->tenant, 5);

    $this->postJson('api/v1/customers', newCustomer())->assertCreated();
});
