<?php

declare(strict_types=1);

use App\Enums\SubscriptionStatus;
use App\Enums\TenantRole;
use App\Models\Customer;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use App\Support\Tenancy\TenantContext;
use Database\Seeders\PlanSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Redis;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->beforeEach(function (): void {
        $this->seed([PlanSeeder::class, RolePermissionSeeder::class]);
        // Rate limits live in Redis, outside the rolled-back test transaction.
        Redis::connection()->flushdb();
    })
    ->in('Feature');

pest()->extend(TestCase::class)->in('Unit');

function createTenant(string $planCode = 'pro', SubscriptionStatus $status = SubscriptionStatus::Active): Tenant
{
    $tenant = Tenant::factory()->create();

    app(TenantContext::class)->run($tenant, function () use ($planCode, $status): void {
        $factory = Subscription::factory()->forPlan($planCode);

        match ($status) {
            SubscriptionStatus::Trialing => $factory->trialing()->create(),
            SubscriptionStatus::Active => $planCode === 'free' ? $factory->activeFree()->create() : $factory->active()->create(),
            SubscriptionStatus::PastDue => $factory->pastDue()->create(),
            SubscriptionStatus::Cancelled => $factory->cancelled()->create(),
            SubscriptionStatus::Expired => $factory->expired()->create(),
        };
    });

    return $tenant;
}

function createTenantUser(Tenant $tenant, TenantRole $role = TenantRole::Owner): User
{
    $user = User::factory()->forTenant($tenant)->create();

    app(TenantContext::class)->run($tenant, fn () => $user->assignRole($role->value));

    return $user->refresh();
}

function actingAsTenantUser(TenantRole $role = TenantRole::Owner, string $planCode = 'pro'): User
{
    $user = createTenantUser(createTenant($planCode), $role);
    Sanctum::actingAs($user);

    return $user;
}

function actingAsPlatformAdmin(): User
{
    $admin = User::factory()->platformAdmin()->create()->refresh();
    Sanctum::actingAs($admin);

    return $admin;
}

/**
 * @param  array<string, mixed>  $attributes
 * @return Collection<int, Customer>
 */
function createCustomers(Tenant $tenant, int $count = 1, array $attributes = []): Collection
{
    return app(TenantContext::class)->run(
        $tenant,
        fn (): Collection => Customer::factory()->count($count)->create($attributes),
    );
}
