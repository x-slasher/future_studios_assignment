<?php

declare(strict_types=1);

use App\Enums\SubscriptionStatus;
use App\Models\Subscription;
use Carbon\CarbonImmutable;

it('decides whether writes are allowed', function (SubscriptionStatus $status, array $dates, bool $usable): void {
    $now = CarbonImmutable::parse('2026-10-02 12:00:00');
    $subscription = new Subscription(['status' => $status, ...$dates]);

    expect($subscription->isUsable($now))->toBe($usable);
})->with([
    'trialing, trial ends later' => [SubscriptionStatus::Trialing, ['trial_ends_at' => '2026-10-03 12:00:00'], true],
    'trialing, trial ended' => [SubscriptionStatus::Trialing, ['trial_ends_at' => '2026-10-01 12:00:00'], false],
    'trialing, trial ends exactly now' => [SubscriptionStatus::Trialing, ['trial_ends_at' => '2026-10-02 12:00:00'], false],
    'active, period running' => [SubscriptionStatus::Active, ['current_period_end' => '2026-10-30 00:00:00'], true],
    'active, period ended (job not run yet)' => [SubscriptionStatus::Active, ['current_period_end' => '2026-10-01 00:00:00'], true],
    'active, free plan with no period' => [SubscriptionStatus::Active, [], true],
    'past_due, inside grace' => [SubscriptionStatus::PastDue, ['current_period_end' => '2026-10-01 00:00:00'], true],
    'cancelled, access until later' => [SubscriptionStatus::Cancelled, ['ends_at' => '2026-10-10 00:00:00'], true],
    'cancelled, access ended' => [SubscriptionStatus::Cancelled, ['ends_at' => '2026-10-01 00:00:00'], false],
    'expired, period in the past' => [SubscriptionStatus::Expired, ['current_period_end' => '2026-09-01 00:00:00'], false],
    'expired, dates in the future' => [SubscriptionStatus::Expired, ['ends_at' => '2026-11-01 00:00:00'], false],
]);
