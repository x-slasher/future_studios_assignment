<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\FeatureGate;
use App\Enums\CustomerStatus;
use App\Enums\FeatureKey;
use App\Enums\SubscriptionStatus;
use App\Models\Subscription;
use App\Repositories\Contracts\SubscriptionRepositoryInterface;
use App\Repositories\Contracts\UsageRepositoryInterface;
use App\Support\Cache\CacheScope;
use App\Support\Cache\VersionedCache;
use App\Support\Tenancy\TenantContext;
use Carbon\CarbonImmutable;

final class TenantDashboardService
{
    private const int TTL_SECONDS = 600;

    private const int TREND_DAYS = 30;

    public function __construct(
        private readonly SubscriptionRepositoryInterface $subscriptions,
        private readonly UsageRepositoryInterface $usage,
        private readonly SubscriptionService $subscriptionService,
        private readonly FeatureGate $features,
        private readonly VersionedCache $cache,
        private readonly TenantContext $context,
    ) {}

    /** @return array<string, mixed> */
    public function summary(): array
    {
        return $this->cache->remember(
            CacheScope::tenant($this->context->id()),
            'dashboard',
            self::TTL_SECONDS,
            fn (): array => $this->build(CarbonImmutable::now()),
        );
    }

    /** @return array<string, mixed> */
    private function build(CarbonImmutable $now): array
    {
        return [
            'subscription' => $this->subscriptionBlock($this->subscriptions->currentWithPlan(), $now),
            'usage' => $this->subscriptionService->usage(),
            'customers' => $this->customerBlock($now),
            'trend' => $this->features->isEnabled($this->context->tenant(), FeatureKey::AnalyticsTrends)
                ? $this->usage->trend(self::TREND_DAYS)
                : null,
        ];
    }

    /** @return array<string, mixed> */
    private function subscriptionBlock(Subscription $subscription, CarbonImmutable $now): array
    {
        $accessEndsAt = match ($subscription->status) {
            SubscriptionStatus::Trialing => $subscription->trial_ends_at,
            SubscriptionStatus::Cancelled => $subscription->ends_at,
            default => $subscription->current_period_end,
        };

        return [
            'status' => $subscription->status->value,
            'plan' => $subscription->plan->code,
            'current_period_end' => $subscription->current_period_end?->toIso8601ZuluString(),
            'days_left' => $accessEndsAt === null ? null : max(0, (int) ceil($now->diffInDays($accessEndsAt, false))),
        ];
    }

    /** @return array{total: int, active: int, inactive: int, new_last_30_days: int} */
    private function customerBlock(CarbonImmutable $now): array
    {
        $byStatus = $this->usage->customerCountsByStatus();
        $active = $byStatus[CustomerStatus::Active->value] ?? 0;
        $inactive = $byStatus[CustomerStatus::Inactive->value] ?? 0;

        return [
            'total' => $active + $inactive,
            'active' => $active,
            'inactive' => $inactive,
            'new_last_30_days' => $this->usage->newCustomersSince($now->subDays(30)),
        ];
    }
}
