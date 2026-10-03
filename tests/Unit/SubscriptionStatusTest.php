<?php

declare(strict_types=1);

use App\Enums\SubscriptionStatus as S;

it('allows only the transitions in the table', function (S $from, S $to, bool $allowed): void {
    expect($from->canTransitionTo($to))->toBe($allowed);
})->with([
    'trialing -> trialing' => [S::Trialing, S::Trialing, false],
    'trialing -> active' => [S::Trialing, S::Active, true],
    'trialing -> past_due' => [S::Trialing, S::PastDue, false],
    'trialing -> cancelled' => [S::Trialing, S::Cancelled, true],
    'trialing -> expired' => [S::Trialing, S::Expired, true],
    'active -> trialing' => [S::Active, S::Trialing, false],
    'active -> active' => [S::Active, S::Active, false],
    'active -> past_due' => [S::Active, S::PastDue, true],
    'active -> cancelled' => [S::Active, S::Cancelled, true],
    'active -> expired' => [S::Active, S::Expired, false],
    'past_due -> trialing' => [S::PastDue, S::Trialing, false],
    'past_due -> active' => [S::PastDue, S::Active, true],
    'past_due -> past_due' => [S::PastDue, S::PastDue, false],
    'past_due -> cancelled' => [S::PastDue, S::Cancelled, true],
    'past_due -> expired' => [S::PastDue, S::Expired, true],
    'cancelled -> trialing' => [S::Cancelled, S::Trialing, false],
    'cancelled -> active' => [S::Cancelled, S::Active, true],
    'cancelled -> past_due' => [S::Cancelled, S::PastDue, false],
    'cancelled -> cancelled' => [S::Cancelled, S::Cancelled, false],
    'cancelled -> expired' => [S::Cancelled, S::Expired, true],
    'expired -> trialing' => [S::Expired, S::Trialing, false],
    'expired -> active' => [S::Expired, S::Active, true],
    'expired -> past_due' => [S::Expired, S::PastDue, false],
    'expired -> cancelled' => [S::Expired, S::Cancelled, false],
    'expired -> expired' => [S::Expired, S::Expired, false],
]);
