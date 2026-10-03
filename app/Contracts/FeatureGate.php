<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Enums\FeatureKey;
use App\Exceptions\FeatureNotAvailable;
use App\Exceptions\PlanLimitExceeded;
use App\Models\Tenant;

interface FeatureGate
{
    public function isEnabled(Tenant $tenant, FeatureKey $feature): bool;

    public function limit(Tenant $tenant, FeatureKey $feature): ?int;

    /** @throws FeatureNotAvailable */
    public function ensureEnabled(Tenant $tenant, FeatureKey $feature): void;

    /** @throws PlanLimitExceeded */
    public function ensureWithinLimit(Tenant $tenant, FeatureKey $feature, int $currentCount): void;
}
