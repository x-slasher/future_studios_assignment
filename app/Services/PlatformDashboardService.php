<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\SubscriptionStatus;
use App\Enums\TenantStatus;
use App\Repositories\Contracts\SubscriptionRepositoryInterface;
use App\Repositories\Contracts\TenantRepositoryInterface;
use App\Support\Cache\CacheScope;
use App\Support\Cache\VersionedCache;
use Carbon\CarbonImmutable;

final class PlatformDashboardService
{
    private const int TTL_SECONDS = 300;

    public function __construct(
        private readonly TenantRepositoryInterface $tenants,
        private readonly SubscriptionRepositoryInterface $subscriptions,
        private readonly VersionedCache $cache,
    ) {}

    /** @return array<string, mixed> */
    public function summary(): array
    {
        return $this->cache->remember(
            CacheScope::platform(),
            'dashboard',
            self::TTL_SECONDS,
            fn (): array => $this->build(CarbonImmutable::now()->subDays(30)),
        );
    }

    /** @return array<string, mixed> */
    private function build(CarbonImmutable $thirtyDaysAgo): array
    {
        return [
            'tenants' => $this->tenantBlock($thirtyDaysAgo),
            'subscriptions_by_status' => $this->withEveryStatus($this->subscriptions->countsByStatusAcrossTenants()),
            'subscriptions_by_plan' => $this->subscriptions->countsByPlanAcrossTenants(),
            'mrr_cents' => $this->subscriptions->mrrCentsAcrossTenants(),
            'churned_last_30_days' => $this->subscriptions->churnedSinceAcrossTenants($thirtyDaysAgo),
        ];
    }

    /** @return array{total: int, active: int, suspended: int, new_last_30_days: int} */
    private function tenantBlock(CarbonImmutable $thirtyDaysAgo): array
    {
        $byStatus = $this->tenants->countsByStatus();
        $active = $byStatus[TenantStatus::Active->value] ?? 0;
        $suspended = $byStatus[TenantStatus::Suspended->value] ?? 0;

        return [
            'total' => $active + $suspended,
            'active' => $active,
            'suspended' => $suspended,
            'new_last_30_days' => $this->tenants->countCreatedSince($thirtyDaysAgo),
        ];
    }

    /**
     * @param  array<string, int>  $counts
     * @return array<string, int>
     */
    private function withEveryStatus(array $counts): array
    {
        $all = [];

        foreach (SubscriptionStatus::cases() as $status) {
            $all[$status->value] = $counts[$status->value] ?? 0;
        }

        return $all;
    }
}
