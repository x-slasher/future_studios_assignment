<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use Carbon\CarbonImmutable;

interface UsageRepositoryInterface
{
    public function userCount(): int;

    public function customerCount(): int;

    public function customerCountsByStatus(): array;

    public function newCustomersSince(CarbonImmutable $since): int;

    /** @return list<array{date: string, users: int, customers: int}> */
    public function trend(int $days): array;

    /** @return array<int, int> tenant ID => non-deleted users */
    public function userCountsAcrossTenants(): array;

    /** @return array<int, int> tenant ID => non-deleted customers */
    public function customerCountsAcrossTenants(): array;

    /** @param list<array{tenant_id: int, snapshot_date: string, users_count: int, customers_count: int}> $rows */
    public function upsertSnapshotsAcrossTenants(array $rows): void;
}
