<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\DTOs\TenantFilter;
use App\Models\Tenant;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TenantRepositoryInterface
{
    /** @param array<string, mixed> $attributes */
    public function create(array $attributes): Tenant;

    /** @param array<string, mixed> $attributes */
    public function update(Tenant $tenant, array $attributes): Tenant;

    public function findById(int $id): Tenant;

    public function findLocked(int $id): Tenant;

    /** @return LengthAwarePaginator<int, Tenant> */
    public function paginateAcrossTenants(TenantFilter $filter): LengthAwarePaginator;

    /** @return list<int> */
    public function activeIds(): array;

    public function countsByStatus(): array;

    public function countCreatedSince(CarbonImmutable $since): int;
}
