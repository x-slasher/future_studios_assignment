<?php

declare(strict_types=1);

use App\DTOs\CreateCustomerData;
use App\Enums\CustomerStatus;
use App\Enums\TenantRole;
use App\Services\CustomerService;
use App\Support\Cache\CacheScope;
use App\Support\Cache\VersionedCache;
use App\Support\Tenancy\TenantContext;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;

function tenantVersion(int $tenantId): int
{
    return app(VersionedCache::class)->version(CacheScope::tenant($tenantId));
}

/** @return list<string> */
function queriesDuring(Closure $callback): array
{
    DB::flushQueryLog();
    DB::enableQueryLog();
    $callback();
    DB::disableQueryLog();

    return array_column(DB::getQueryLog(), 'query');
}

it('serves the second dashboard call without touching customers or snapshots', function (): void {
    $user = actingAsTenantUser(TenantRole::Owner, 'pro');
    createCustomers($user->tenant, 3);

    $first = queriesDuring(fn () => $this->getJson('api/v1/dashboard')->assertOk());
    $second = queriesDuring(fn () => $this->getJson('api/v1/dashboard')->assertOk());

    $touchesDashboardTables = fn (string $sql): bool => Str::contains($sql, ['`customers`', '`daily_usage_snapshots`']);
    expect(array_filter($first, $touchesDashboardTables))->not->toBeEmpty()
        ->and(array_filter($second, $touchesDashboardTables))->toBeEmpty();
});

it('bumps the tenant version on a customer create, and the next dashboard shows the new count', function (): void {
    $user = actingAsTenantUser(TenantRole::Owner, 'pro');
    $this->getJson('api/v1/dashboard')->assertJsonPath('data.customers.total', 0);
    $before = tenantVersion($user->tenant_id);

    $this->postJson('api/v1/customers', ['name' => 'New', 'email' => 'new@store.test'])->assertCreated();

    expect(tenantVersion($user->tenant_id))->toBe($before + 1);
    $this->getJson('api/v1/dashboard')->assertJsonPath('data.customers.total', 1);
});

it('does not bump the version when the write is rolled back', function (): void {
    $user = actingAsTenantUser(TenantRole::Owner, 'pro');
    $before = tenantVersion($user->tenant_id);

    try {
        DB::transaction(function () use ($user): void {
            app(TenantContext::class)->run($user->tenant, fn () => app(CustomerService::class)->create(
                new CreateCustomerData('Ghost', 'ghost@store.test', null, null, CustomerStatus::Active),
                $user,
            ));

            throw new RuntimeException('roll back');
        });
    } catch (RuntimeException) {
    }

    expect(tenantVersion($user->tenant_id))->toBe($before);
    $this->assertDatabaseMissing('customers', ['email' => 'ghost@store.test']);
});

it('does not bump another tenant version', function (): void {
    $a = actingAsTenantUser(TenantRole::Owner, 'pro');
    $b = createTenantUser(createTenant('pro'), TenantRole::Owner);
    $bBefore = tenantVersion($b->tenant_id);

    $this->postJson('api/v1/customers', ['name' => 'Mine', 'email' => 'mine@store.test'])->assertCreated();

    expect(tenantVersion($a->tenant_id))->toBe(1)
        ->and(tenantVersion($b->tenant_id))->toBe($bBefore);
});

it('bumps the platform version on an admin plan update', function (): void {
    actingAsPlatformAdmin();
    $before = app(VersionedCache::class)->version(CacheScope::platform());
    $planUlid = DB::table('plans')->where('code', 'starter')->value('ulid');

    $this->patchJson("api/v1/admin/plans/{$planUlid}", ['name' => 'Starter Plus'])->assertOk();

    expect(app(VersionedCache::class)->version(CacheScope::platform()))->toBe($before + 1);
});

it('refreshes the cached public plan list after an admin edit', function (): void {
    $this->getJson('api/v1/plans')->assertJsonPath('data.1.name', 'Starter');

    actingAsPlatformAdmin();
    $planUlid = DB::table('plans')->where('code', 'starter')->value('ulid');
    $this->patchJson("api/v1/admin/plans/{$planUlid}", ['name' => 'Starter Plus'])->assertOk();

    $this->getJson('api/v1/plans')->assertJsonPath('data.1.name', 'Starter Plus');
});

it('refreshes the cached admin dashboard when a tenant registers', function (): void {
    actingAsPlatformAdmin();
    $this->getJson('api/v1/admin/dashboard')->assertJsonPath('data.tenants.total', 0);

    $this->postJson('api/v1/auth/register', [
        'company_name' => 'New Co', 'name' => 'Owner', 'email' => 'owner@newco.test',
        'password' => 'Secret123', 'password_confirmation' => 'Secret123',
    ])->assertCreated();

    actingAsPlatformAdmin();
    $this->getJson('api/v1/admin/dashboard')->assertJsonPath('data.tenants.total', 1);
});

it('keeps the dashboard of an unrelated tenant cached', function (): void {
    $a = actingAsTenantUser(TenantRole::Owner, 'pro');
    $this->getJson('api/v1/dashboard')->assertOk();

    Sanctum::actingAs(createTenantUser(createTenant('pro'), TenantRole::Owner));
    $this->postJson('api/v1/customers', ['name' => 'Theirs', 'email' => 'theirs@store.test'])->assertCreated();

    Sanctum::actingAs($a);
    $queries = queriesDuring(fn () => $this->getJson('api/v1/dashboard')->assertJsonPath('data.customers.total', 0));
    expect(array_filter($queries, fn (string $sql): bool => str_contains($sql, '`customers`')))->toBeEmpty();
});
