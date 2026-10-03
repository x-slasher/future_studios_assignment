<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\FeatureGate;
use App\Enums\FeatureKey;
use App\Enums\SubscriptionEventType;
use App\Enums\SubscriptionStatus;
use App\Events\SubscriptionChanged;
use App\Exceptions\InvalidSubscriptionTransition;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Repositories\Contracts\PlanRepositoryInterface;
use App\Repositories\Contracts\SubscriptionRepositoryInterface;
use App\Repositories\Contracts\UsageRepositoryInterface;
use App\Support\Tenancy\TenantContext;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

final class SubscriptionService
{
    public function __construct(
        private readonly SubscriptionRepositoryInterface $subscriptions,
        private readonly PlanRepositoryInterface $plans,
        private readonly UsageRepositoryInterface $usage,
        private readonly FeatureGate $features,
        private readonly TenantContext $context,
    ) {}

    /** @return array{subscription: Subscription, usage: array<string, array{used: int, limit: int|null, over_limit: bool}>} */
    public function overview(): array
    {
        return [
            'subscription' => $this->current(),
            'usage' => $this->usage(),
        ];
    }

    public function current(): Subscription
    {
        return $this->subscriptions->currentWithPlan();
    }

    /** @return array<string, array{used: int, limit: int|null, over_limit: bool}> */
    public function usage(): array
    {
        $tenant = $this->context->tenant();

        return [
            'users' => $this->usageLine($tenant, FeatureKey::MaxUsers, $this->usage->userCount()),
            'customers' => $this->usageLine($tenant, FeatureKey::MaxCustomers, $this->usage->customerCount()),
        ];
    }

    public function startTrial(Tenant $tenant, Plan $plan): Subscription
    {
        return $this->context->run($tenant, fn (): Subscription => DB::transaction(function () use ($plan): Subscription {
            $subscription = $this->subscriptions->create($this->startAttributes($plan));
            $this->recordAndAnnounce($subscription, SubscriptionEventType::Created, null, null);

            return $subscription;
        }));
    }

    /** @return array{subscription: Subscription, usage: array<string, array{used: int, limit: int|null, over_limit: bool}>} */
    public function changePlan(string $planCode): array
    {
        DB::transaction(function () use ($planCode): void {
            $subscription = $this->subscriptions->findLockedForCurrentTenant();
            $plan = $this->plans->findActiveByCode($planCode);

            $this->ensurePlanCanChange($subscription, $plan);
            $this->apply($subscription, SubscriptionEventType::PlanChanged, $this->planChangeAttributes($plan));
        });

        return $this->overview();
    }

    /** @return array{subscription: Subscription, usage: array<string, array{used: int, limit: int|null, over_limit: bool}>} */
    public function renew(?string $planCode): array
    {
        DB::transaction(function () use ($planCode): void {
            $subscription = $this->subscriptions->findLockedForCurrentTenant();
            $plan = $planCode === null ? $subscription->plan : $this->plans->findActiveByCode($planCode);

            $this->ensureCanRenew($subscription, $plan);
            $this->apply($subscription, SubscriptionEventType::Renewed, $this->renewAttributes($plan));
        });

        return $this->overview();
    }

    /** @return array{subscription: Subscription, usage: array<string, array{used: int, limit: int|null, over_limit: bool}>} */
    public function cancel(): array
    {
        DB::transaction(function (): void {
            $subscription = $this->subscriptions->findLockedForCurrentTenant();

            if ($subscription->plan->isFree()) {
                throw new InvalidSubscriptionTransition('A free plan cannot be cancelled.');
            }

            $now = CarbonImmutable::now();
            $this->apply($subscription, SubscriptionEventType::Cancelled, [
                'status' => SubscriptionStatus::Cancelled,
                'cancelled_at' => $now,
                'ends_at' => $this->accessEndsAt($subscription, $now),
            ]);
        });

        return $this->overview();
    }

    public function processLifecycle(CarbonImmutable $now): void
    {
        $graceCutoff = $now->subDays((int) config('billing.grace_days'));

        $this->eachInTenant($this->subscriptions->dueTrialsAcrossTenants($now), fn () => $this->expire($now));
        $this->eachInTenant($this->subscriptions->duePeriodsAcrossTenants($now), fn () => $this->markPastDue($now));
        $this->eachInTenant($this->subscriptions->pastGraceAcrossTenants($graceCutoff), fn () => $this->expire($now));
        $this->eachInTenant($this->subscriptions->endedCancellationsAcrossTenants($now), fn () => $this->expire($now));
    }

    public function markPastDue(CarbonImmutable $now): void
    {
        DB::transaction(function () use ($now): void {
            $subscription = $this->subscriptions->findLockedForCurrentTenant();

            if ($this->isDueForPastDue($subscription, $now)) {
                $this->apply($subscription, SubscriptionEventType::PastDue, ['status' => SubscriptionStatus::PastDue]);
            }
        });
    }

    public function expire(CarbonImmutable $now): void
    {
        DB::transaction(function () use ($now): void {
            $subscription = $this->subscriptions->findLockedForCurrentTenant();

            if ($this->isDueForExpiry($subscription, $now)) {
                $this->apply($subscription, SubscriptionEventType::Expired, ['status' => SubscriptionStatus::Expired]);
            }
        });
    }

    /** @param iterable<Subscription> $subscriptions */
    private function eachInTenant(iterable $subscriptions, callable $change): void
    {
        foreach ($subscriptions as $subscription) {
            $this->context->run($subscription->tenant, $change);
        }
    }

    private function isDueForPastDue(Subscription $subscription, CarbonImmutable $now): bool
    {
        return $subscription->status === SubscriptionStatus::Active
            && ! $subscription->plan->isFree()
            && $subscription->current_period_end?->lessThanOrEqualTo($now) === true;
    }

    private function isDueForExpiry(Subscription $subscription, CarbonImmutable $now): bool
    {
        $graceDays = (int) config('billing.grace_days');

        return match ($subscription->status) {
            SubscriptionStatus::Trialing => $subscription->trial_ends_at?->lessThanOrEqualTo($now) === true,
            SubscriptionStatus::PastDue => $subscription->current_period_end?->addDays($graceDays)->lessThanOrEqualTo($now) === true,
            SubscriptionStatus::Cancelled => $subscription->ends_at?->lessThanOrEqualTo($now) === true,
            default => false,
        };
    }

    /** @param array<string, mixed> $attributes */
    private function apply(Subscription $subscription, SubscriptionEventType $type, array $attributes): void
    {
        $fromStatus = $subscription->status;
        $fromPlanId = $subscription->plan_id;
        $toStatus = $attributes['status'] ?? $fromStatus;
        $isPlanSwitchOnly = $toStatus === $fromStatus && ($attributes['plan_id'] ?? $fromPlanId) !== $fromPlanId;

        // A plan switch that keeps the status is not a status transition.
        if (! $isPlanSwitchOnly && ! $fromStatus->canTransitionTo($toStatus)) {
            throw new InvalidSubscriptionTransition;
        }

        $this->subscriptions->update($subscription, $attributes);
        $this->recordAndAnnounce($subscription, $type, $fromStatus, $fromPlanId);
    }

    private function recordAndAnnounce(
        Subscription $subscription,
        SubscriptionEventType $type,
        ?SubscriptionStatus $fromStatus,
        ?int $fromPlanId,
    ): void {
        $this->subscriptions->recordEvent([
            'subscription_id' => $subscription->id,
            'type' => $type,
            'from_status' => $fromStatus,
            'to_status' => $subscription->status,
            'from_plan_id' => $fromPlanId,
            'to_plan_id' => $subscription->plan_id,
            'occurred_at' => CarbonImmutable::now(),
        ]);

        SubscriptionChanged::dispatch($subscription->tenant_id, $subscription->id);
    }

    private function ensurePlanCanChange(Subscription $subscription, Plan $plan): void
    {
        if ($plan->id === $subscription->plan_id) {
            throw new InvalidSubscriptionTransition('The subscription is already on this plan.');
        }

        if ($subscription->plan->isFree()) {
            throw new InvalidSubscriptionTransition('Use renew to start a paid plan.');
        }

        $allowed = [SubscriptionStatus::Trialing, SubscriptionStatus::Active, SubscriptionStatus::PastDue];

        if (! in_array($subscription->status, $allowed, true)) {
            throw new InvalidSubscriptionTransition('Renew the subscription to change its plan.');
        }
    }

    private function ensureCanRenew(Subscription $subscription, Plan $plan): void
    {
        if ($plan->isFree()) {
            throw new InvalidSubscriptionTransition('Renew needs a paid plan.');
        }

        if ($subscription->status === SubscriptionStatus::Active && ! $subscription->plan->isFree()) {
            throw new InvalidSubscriptionTransition('The subscription is already active.');
        }
    }

    /** @return array<string, mixed> */
    private function startAttributes(Plan $plan): array
    {
        if ($plan->isFree()) {
            return ['plan_id' => $plan->id, 'status' => SubscriptionStatus::Active];
        }

        return [
            'plan_id' => $plan->id,
            'status' => SubscriptionStatus::Trialing,
            'trial_ends_at' => CarbonImmutable::now()->addDays((int) config('billing.trial_days')),
        ];
    }

    /** @return array<string, mixed> */
    private function planChangeAttributes(Plan $plan): array
    {
        if (! $plan->isFree()) {
            return ['plan_id' => $plan->id];
        }

        return [
            'plan_id' => $plan->id,
            'status' => SubscriptionStatus::Active,
            'trial_ends_at' => null,
            'current_period_start' => null,
            'current_period_end' => null,
        ];
    }

    /** @return array<string, mixed> */
    private function renewAttributes(Plan $plan): array
    {
        $now = CarbonImmutable::now();

        return [
            'plan_id' => $plan->id,
            'status' => SubscriptionStatus::Active,
            'current_period_start' => $now,
            'current_period_end' => $now->addMonth(),
            'trial_ends_at' => null,
            'cancelled_at' => null,
            'ends_at' => null,
        ];
    }

    private function accessEndsAt(Subscription $subscription, CarbonImmutable $now): ?CarbonImmutable
    {
        return match ($subscription->status) {
            SubscriptionStatus::Trialing => $subscription->trial_ends_at,
            SubscriptionStatus::Active => $subscription->current_period_end,
            default => $now,
        };
    }

    /** @return array{used: int, limit: int|null, over_limit: bool} */
    private function usageLine(Tenant $tenant, FeatureKey $feature, int $used): array
    {
        $limit = $this->features->limit($tenant, $feature);

        return ['used' => $used, 'limit' => $limit, 'over_limit' => $limit !== null && $used > $limit];
    }
}
