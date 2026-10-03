<?php

declare(strict_types=1);

use App\Enums\SubscriptionStatus;
use App\Enums\TenantRole;
use App\Models\Tenant;
use Carbon\CarbonImmutable;

function namedTenant(string $name, string $planCode, SubscriptionStatus $status = SubscriptionStatus::Active): Tenant
{
    $tenant = createTenant($planCode, $status);
    $tenant->update(['name' => $name]);

    return $tenant;
}

it('lists tenants with their subscription, filtered by status and plan', function (): void {
    namedTenant('Acme', 'pro');
    namedTenant('Globex', 'free');
    namedTenant('Initech', 'starter', SubscriptionStatus::Trialing)->update(['status' => 'suspended']);
    actingAsPlatformAdmin();

    $this->getJson('api/v1/admin/tenants?sort=name')
        ->assertOk()
        ->assertJsonPath('meta.total', 3)
        ->assertJsonPath('data.*.name', ['Acme', 'Globex', 'Initech'])
        ->assertJsonPath('data.0.subscription.plan.code', 'pro')
        ->assertJsonPath('data.2.subscription.status', 'trialing');

    $this->getJson('api/v1/admin/tenants?filter[status]=suspended')->assertJsonPath('data.*.name', ['Initech']);
    $this->getJson('api/v1/admin/tenants?filter[plan]=free')->assertJsonPath('data.*.name', ['Globex']);
    $this->getJson('api/v1/admin/tenants?filter[status]=active&filter[plan]=starter')->assertJsonCount(0, 'data');
    $this->getJson('api/v1/admin/tenants?filter[search]=Gl')->assertJsonPath('data.*.name', ['Globex']);
});

it('sorts tenants newest first by default', function (): void {
    $this->travelTo(CarbonImmutable::parse('2026-09-01'));
    namedTenant('Older', 'pro');
    $this->travelTo(CarbonImmutable::parse('2026-09-20'));
    namedTenant('Newer', 'pro');
    actingAsPlatformAdmin();

    $this->getJson('api/v1/admin/tenants')->assertJsonPath('data.*.name', ['Newer', 'Older']);
});

it('rejects unknown filters, sorts, and plan codes', function (string $query): void {
    actingAsPlatformAdmin();

    $this->getJson("api/v1/admin/tenants?{$query}")->assertStatus(422);
})->with(['filter[owner]=x', 'sort=status', 'filter[plan]=gold', 'filter[status]=deleted']);

it('shows a tenant with subscription, plan, and usage counts', function (): void {
    $tenant = namedTenant('Acme', 'starter');
    createTenantUser($tenant, TenantRole::Owner);
    createTenantUser($tenant, TenantRole::Member);
    createCustomers($tenant, 7);
    actingAsPlatformAdmin();

    $this->getJson("api/v1/admin/tenants/{$tenant->ulid}")
        ->assertOk()
        ->assertJsonPath('data.id', $tenant->ulid)
        ->assertJsonPath('data.subscription.status', 'active')
        ->assertJsonPath('data.subscription.plan.code', 'starter')
        ->assertJsonPath('data.usage.users', ['used' => 2, 'limit' => 5, 'over_limit' => false])
        ->assertJsonPath('data.usage.customers', ['used' => 7, 'limit' => 500, 'over_limit' => false]);
});

it('returns 404 for an unknown tenant id', function (): void {
    actingAsPlatformAdmin();

    $this->getJson('api/v1/admin/tenants/01J9Z3K7AAAAAAAAAAAAAAAAAA')->assertNotFound();
});

it('suspends a tenant: revokes tokens and blocks login; reactivating restores login', function (): void {
    $tenant = namedTenant('Acme', 'pro');
    $user = createTenantUser($tenant, TenantRole::Owner);
    $user->update(['email' => 'owner@acme.test']);
    $token = $user->createToken('api')->plainTextToken;
    $other = createTenantUser(createTenant('pro'), TenantRole::Owner);
    $other->createToken('api');
    actingAsPlatformAdmin();

    $this->postJson("api/v1/admin/tenants/{$tenant->ulid}/suspend")
        ->assertOk()
        ->assertJsonPath('data.status', 'suspended');

    expect($user->tokens()->count())->toBe(0)
        ->and($other->tokens()->count())->toBe(1)
        ->and($tenant->fresh()->suspended_at)->not->toBeNull();

    app('auth')->forgetGuards();
    $this->withToken($token)->getJson('api/v1/tenant')->assertUnauthorized();
    $this->postJson('api/v1/auth/login', ['email' => 'owner@acme.test', 'password' => 'password'])
        ->assertForbidden()
        ->assertJsonPath('error.code', 'TENANT_SUSPENDED');

    actingAsPlatformAdmin();
    $this->postJson("api/v1/admin/tenants/{$tenant->ulid}/reactivate")
        ->assertOk()
        ->assertJsonPath('data.status', 'active');

    expect($tenant->fresh()->suspended_at)->toBeNull();
    $this->postJson('api/v1/auth/login', ['email' => 'owner@acme.test', 'password' => 'password'])->assertOk();
});

it('blocks a suspended tenant even with a valid session', function (): void {
    $user = actingAsTenantUser(TenantRole::Owner, 'pro');
    $user->tenant->update(['status' => 'suspended']);
    $user->unsetRelation('tenant');

    $this->getJson('api/v1/customers')->assertForbidden()->assertJsonPath('error.code', 'TENANT_SUSPENDED');
});

it('forbids tenant users from admin tenant routes', function (): void {
    $user = actingAsTenantUser(TenantRole::Owner);

    $this->getJson('api/v1/admin/tenants')->assertForbidden();
    $this->postJson("api/v1/admin/tenants/{$user->tenant->ulid}/suspend")->assertForbidden();
});
