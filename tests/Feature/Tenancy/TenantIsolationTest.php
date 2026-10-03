<?php

declare(strict_types=1);

use App\Enums\TenantRole;
use App\Exceptions\TenantContextMissing;
use App\Models\Customer;
use App\Models\CustomerExport;
use App\Models\DailyUsageSnapshot;
use App\Models\Subscription;
use App\Models\SubscriptionEvent;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;

it('throws when a tenant model is queried with no tenant context', function (string $model): void {
    $model::query()->count();
})->with([
    Customer::class,
    CustomerExport::class,
    Subscription::class,
    SubscriptionEvent::class,
    DailyUsageSnapshot::class,
])->throws(TenantContextMissing::class);

it('returns 404 when a user of tenant A shows, updates, or deletes a user of tenant B', function (string $method): void {
    actingAsTenantUser(TenantRole::Owner);
    $other = createTenantUser(createTenant(), TenantRole::Member);

    $this->json($method, "api/v1/users/{$other->ulid}", ['name' => 'Hacked'])
        ->assertNotFound()
        ->assertJsonPath('error.code', 'NOT_FOUND');

    expect(User::query()->find($other->id))->name->not->toBe('Hacked')->deleted_at->toBeNull();
})->with(['GET', 'PATCH', 'DELETE']);

it('lists only the current tenant users', function (): void {
    $owner = actingAsTenantUser(TenantRole::Owner);
    createTenantUser(createTenant(), TenantRole::Member);
    createTenantUser(createTenant(), TenantRole::Member);

    $this->getJson('api/v1/users')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', $owner->ulid);
});

it('returns 403 for a platform admin on tenant routes', function (string $uri): void {
    actingAsPlatformAdmin();

    $this->getJson($uri)->assertForbidden()->assertJsonPath('error.code', 'FORBIDDEN');
})->with(['api/v1/users', 'api/v1/tenant', 'api/v1/subscription']);

it('returns 403 for a tenant owner on admin routes', function (): void {
    actingAsTenantUser(TenantRole::Owner);

    $this->getJson('api/v1/admin/plans')->assertForbidden()->assertJsonPath('error.code', 'FORBIDDEN');
});

it('returns 404 when a user of tenant A shows, updates, or deletes a customer of tenant B', function (string $method): void {
    actingAsTenantUser(TenantRole::Owner);
    $theirs = createCustomers(createTenant())->first();

    $this->json($method, "api/v1/customers/{$theirs->ulid}", ['name' => 'Hacked'])
        ->assertNotFound()
        ->assertJsonPath('error.code', 'NOT_FOUND')
        ->assertJsonPath('error.message', 'The requested resource was not found.');

    $row = DB::table('customers')->where('id', $theirs->id)->first();
    expect($row->name)->not->toBe('Hacked')->and($row->deleted_at)->toBeNull();
})->with(['GET', 'PATCH', 'DELETE']);

it('lists only tenant A customers when both tenants have data', function (): void {
    $user = actingAsTenantUser(TenantRole::Owner);
    $mine = createCustomers($user->tenant, 3);
    createCustomers(createTenant(), 5);

    $this->getJson('api/v1/customers')
        ->assertOk()
        ->assertJsonPath('meta.total', 3)
        ->assertJsonPath('data.*.id', $mine->sortByDesc('id')->pluck('ulid')->values()->all());
});

it('hides tenant B exports from tenant A', function (): void {
    Queue::fake();
    $other = createTenantUser(createTenant('pro'), TenantRole::Owner);
    $this->actingAs($other, 'sanctum');
    $theirs = $this->postJson('api/v1/customers/exports')->assertStatus(202)->json('data.id');

    $this->actingAs(createTenantUser(createTenant('pro'), TenantRole::Owner), 'sanctum');

    $this->getJson("api/v1/customers/exports/{$theirs}")->assertNotFound();
    $this->getJson("api/v1/customers/exports/{$theirs}/download")->assertNotFound();
});

it('ignores a tenant_id sent in the body when creating a customer', function (): void {
    $user = actingAsTenantUser(TenantRole::Owner);
    $victim = createTenant();

    $id = $this->postJson('api/v1/customers', ['name' => 'Sneaky', 'email' => 'sneaky@x.test', 'tenant_id' => $victim->id])
        ->assertCreated()
        ->json('data.id');

    expect(DB::table('customers')->where('ulid', $id)->value('tenant_id'))->toBe($user->tenant_id);
});
