<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\FeatureGate;
use App\Enums\FeatureKey;
use App\Exceptions\FeatureNotAvailable;
use App\Exceptions\PlanLimitExceeded;
use App\Models\Tenant;
use App\Repositories\Contracts\PlanRepositoryInterface;
use App\Support\Cache\CacheScope;
use App\Support\Cache\VersionedCache;

final class PlanFeatureGate implements FeatureGate
{
    private const int TTL_SECONDS = 3600;

    public function __construct(
        private readonly PlanRepositoryInterface $plans,
        private readonly VersionedCache $cache,
    ) {}

    public function isEnabled(Tenant $tenant, FeatureKey $feature): bool
    {
        return $this->features($tenant)[$feature->value]['enabled'] ?? false;
    }

    public function limit(Tenant $tenant, FeatureKey $feature): ?int
    {
        if (! $this->isEnabled($tenant, $feature)) {
            return 0;
        }

        return $this->features($tenant)[$feature->value]['limit'];
    }

    public function ensureEnabled(Tenant $tenant, FeatureKey $feature): void
    {
        if (! $this->isEnabled($tenant, $feature)) {
            throw new FeatureNotAvailable($feature);
        }
    }

    public function ensureWithinLimit(Tenant $tenant, FeatureKey $feature, int $currentCount): void
    {
        $limit = $this->limit($tenant, $feature);

        if ($limit !== null && $currentCount >= $limit) {
            throw new PlanLimitExceeded($feature, $limit, $currentCount);
        }
    }

    /** @return array<string, array{enabled: bool, limit: int|null}> */
    private function features(Tenant $tenant): array
    {
        // The platform version is in the key, so one plan edit refreshes every tenant.
        $name = 'features:pv'.$this->cache->version(CacheScope::platform());

        return $this->cache->remember(
            CacheScope::tenant($tenant->id),
            $name,
            self::TTL_SECONDS,
            fn (): array => $this->plans->featureMapForTenant($tenant->id),
        );
    }
}
