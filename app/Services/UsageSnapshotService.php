<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\Contracts\TenantRepositoryInterface;
use App\Repositories\Contracts\UsageRepositoryInterface;
use Carbon\CarbonImmutable;

final class UsageSnapshotService
{
    public function __construct(
        private readonly UsageRepositoryInterface $usage,
        private readonly TenantRepositoryInterface $tenants,
    ) {}

    public function takeDaily(CarbonImmutable $date): void
    {
        $users = $this->usage->userCountsAcrossTenants();
        $customers = $this->usage->customerCountsAcrossTenants();

        $rows = array_map(fn (int $tenantId): array => [
            'tenant_id' => $tenantId,
            'snapshot_date' => $date->toDateString(),
            'users_count' => $users[$tenantId] ?? 0,
            'customers_count' => $customers[$tenantId] ?? 0,
        ], $this->tenants->activeIds());

        $this->usage->upsertSnapshotsAcrossTenants($rows);
    }
}
