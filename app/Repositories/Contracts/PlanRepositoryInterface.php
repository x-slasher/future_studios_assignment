<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Enums\FeatureKey;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Collection;

interface PlanRepositoryInterface
{
    /** @return Collection<int, Plan> */
    public function activeWithFeatures(): Collection;

    /** @return Collection<int, Plan> */
    public function allWithFeatures(): Collection;

    public function findActiveByCode(string $code): Plan;

    /** @param array<string, mixed> $attributes */
    public function create(array $attributes): Plan;

    /** @param array<string, mixed> $attributes */
    public function update(Plan $plan, array $attributes): Plan;

    /** @param  list<array{feature_key: FeatureKey, is_enabled: bool, limit_value: int|null}>  $features */
    public function syncFeatures(Plan $plan, array $features): void;

    /** @return array<string, array{enabled: bool, limit: int|null}> */
    public function featureMapForTenant(int $tenantId): array;
}
