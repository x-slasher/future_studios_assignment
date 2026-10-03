<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Enums\SubscriptionEventType;
use App\Enums\SubscriptionStatus;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\SubscriptionEvent;
use App\Repositories\Contracts\SubscriptionRepositoryInterface;
use App\Support\Tenancy\TenantScope;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\LazyCollection;

final class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    private const int LIFECYCLE_CHUNK = 200;

    public function currentWithPlan(): Subscription
    {
        return Subscription::query()->with('plan.features')->firstOrFail();
    }

    public function findLockedForCurrentTenant(): Subscription
    {
        return Subscription::query()->with('plan')->lockForUpdate()->firstOrFail();
    }

    public function create(array $attributes): Subscription
    {
        return Subscription::query()->create($attributes);
    }

    public function update(Subscription $subscription, array $attributes): Subscription
    {
        $subscription->update($attributes);

        return $subscription;
    }

    public function recordEvent(array $attributes): void
    {
        SubscriptionEvent::query()->create($attributes);
    }

    public function dueTrialsAcrossTenants(CarbonImmutable $now): LazyCollection
    {
        return $this->lifecycleAcrossTenants(SubscriptionStatus::Trialing)
            ->where('trial_ends_at', '<=', $now)
            ->lazyById(self::LIFECYCLE_CHUNK);
    }

    public function duePeriodsAcrossTenants(CarbonImmutable $now): LazyCollection
    {
        return $this->lifecycleAcrossTenants(SubscriptionStatus::Active)
            ->where('current_period_end', '<=', $now)
            ->whereHas('plan', fn (Builder $q): Builder => $q->where('price_cents', '>', 0))
            ->lazyById(self::LIFECYCLE_CHUNK);
    }

    public function pastGraceAcrossTenants(CarbonImmutable $cutoff): LazyCollection
    {
        return $this->lifecycleAcrossTenants(SubscriptionStatus::PastDue)
            ->where('current_period_end', '<=', $cutoff)
            ->lazyById(self::LIFECYCLE_CHUNK);
    }

    public function endedCancellationsAcrossTenants(CarbonImmutable $now): LazyCollection
    {
        return $this->lifecycleAcrossTenants(SubscriptionStatus::Cancelled)
            ->where('ends_at', '<=', $now)
            ->lazyById(self::LIFECYCLE_CHUNK);
    }

    public function countsByStatusAcrossTenants(): array
    {
        return Subscription::withoutGlobalScope(TenantScope::class)
            ->toBase()
            ->select('status')
            ->selectRaw('COUNT(*) AS total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->map(fn (mixed $total): int => (int) $total)
            ->all();
    }

    public function countsByPlanAcrossTenants(): array
    {
        return Plan::query()
            ->leftJoin('subscriptions', 'subscriptions.plan_id', '=', 'plans.id')
            ->toBase()
            ->select('plans.code')
            ->selectRaw('COUNT(subscriptions.id) AS total')
            ->groupBy('plans.id', 'plans.code', 'plans.sort_order')
            ->orderBy('plans.sort_order')
            ->pluck('total', 'code')
            ->map(fn (mixed $total): int => (int) $total)
            ->all();
    }

    public function mrrCentsAcrossTenants(): int
    {
        return (int) Subscription::withoutGlobalScope(TenantScope::class)
            ->join('plans', 'plans.id', '=', 'subscriptions.plan_id')
            ->whereIn('subscriptions.status', [SubscriptionStatus::Active, SubscriptionStatus::PastDue])
            ->where('plans.price_cents', '>', 0)
            ->sum('plans.price_cents');
    }

    public function churnedSinceAcrossTenants(CarbonImmutable $since): int
    {
        return SubscriptionEvent::withoutGlobalScope(TenantScope::class)
            ->whereIn('type', [SubscriptionEventType::Expired, SubscriptionEventType::Cancelled])
            ->where('occurred_at', '>=', $since)
            ->count();
    }

    /** @return Builder<Subscription> */
    private function lifecycleAcrossTenants(SubscriptionStatus $status): Builder
    {
        return Subscription::withoutGlobalScope(TenantScope::class)
            ->with('tenant')
            ->where('status', $status);
    }
}
