<?php

declare(strict_types=1);

use App\Enums\FeatureKey;
use App\Exceptions\FeatureNotAvailable;
use App\Exceptions\PlanLimitExceeded;
use App\Models\Tenant;
use App\Repositories\Contracts\PlanRepositoryInterface;
use App\Services\PlanFeatureGate;
use App\Support\Cache\VersionedCache;
use Illuminate\Cache\ArrayStore;
use Illuminate\Cache\Repository;

function gateWithFeatures(array $features): PlanFeatureGate
{
    $plans = Mockery::mock(PlanRepositoryInterface::class);
    $plans->allows('featureMapForTenant')->andReturn($features);

    return new PlanFeatureGate($plans, new VersionedCache(new Repository(new ArrayStore)));
}

beforeEach(function (): void {
    $this->tenant = new Tenant;
    $this->tenant->id = 1;
});

it('passes at limit - 1 and throws at the limit', function (): void {
    $gate = gateWithFeatures(['max_users' => ['enabled' => true, 'limit' => 5]]);

    $gate->ensureWithinLimit($this->tenant, FeatureKey::MaxUsers, 4);

    expect(fn () => $gate->ensureWithinLimit($this->tenant, FeatureKey::MaxUsers, 5))
        ->toThrow(PlanLimitExceeded::class);
});

it('never throws for a NULL (unlimited) limit', function (): void {
    $gate = gateWithFeatures(['max_customers' => ['enabled' => true, 'limit' => null]]);

    $gate->ensureWithinLimit($this->tenant, FeatureKey::MaxCustomers, 1_000_000);

    expect($gate->limit($this->tenant, FeatureKey::MaxCustomers))->toBeNull();
});

it('treats a missing feature row as disabled', function (): void {
    $gate = gateWithFeatures([]);

    expect($gate->isEnabled($this->tenant, FeatureKey::CustomerExport))->toBeFalse()
        ->and($gate->limit($this->tenant, FeatureKey::MaxUsers))->toBe(0)
        ->and(fn () => $gate->ensureEnabled($this->tenant, FeatureKey::CustomerExport))->toThrow(FeatureNotAvailable::class)
        ->and(fn () => $gate->ensureWithinLimit($this->tenant, FeatureKey::MaxUsers, 0))->toThrow(PlanLimitExceeded::class);
});

it('reads an enabled on/off feature', function (): void {
    $gate = gateWithFeatures(['customer_export' => ['enabled' => true, 'limit' => null]]);

    $gate->ensureEnabled($this->tenant, FeatureKey::CustomerExport);

    expect($gate->isEnabled($this->tenant, FeatureKey::CustomerExport))->toBeTrue();
});

it('puts the limit and current count in the error details', function (): void {
    $gate = gateWithFeatures(['max_customers' => ['enabled' => true, 'limit' => 50]]);

    try {
        $gate->ensureWithinLimit($this->tenant, FeatureKey::MaxCustomers, 50);
    } catch (PlanLimitExceeded $e) {
        expect($e->details())->toBe(['feature' => 'max_customers', 'limit' => 50, 'current' => 50])
            ->and($e->getMessage())->toBe('Your plan allows 50 customers. Upgrade your plan to add more.');

        return;
    }

    $this->fail('PlanLimitExceeded was not thrown.');
});
