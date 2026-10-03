<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\Subscription;
use Carbon\CarbonImmutable;
use Illuminate\Support\LazyCollection;

interface SubscriptionRepositoryInterface
{
    public function currentWithPlan(): Subscription;

    public function findLockedForCurrentTenant(): Subscription;

    /** @param array<string, mixed> $attributes */
    public function create(array $attributes): Subscription;

    /** @param array<string, mixed> $attributes */
    public function update(Subscription $subscription, array $attributes): Subscription;

    /** @param array<string, mixed> $attributes */
    public function recordEvent(array $attributes): void;

    public function dueTrialsAcrossTenants(CarbonImmutable $now): LazyCollection;

    public function duePeriodsAcrossTenants(CarbonImmutable $now): LazyCollection;

    public function pastGraceAcrossTenants(CarbonImmutable $cutoff): LazyCollection;

    public function endedCancellationsAcrossTenants(CarbonImmutable $now): LazyCollection;

    /** @return array<string, int> status => count */
    public function countsByStatusAcrossTenants(): array;

    public function countsByPlanAcrossTenants(): array;

    public function mrrCentsAcrossTenants(): int;

    public function churnedSinceAcrossTenants(CarbonImmutable $since): int;
}
