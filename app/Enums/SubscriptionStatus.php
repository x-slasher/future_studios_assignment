<?php

declare(strict_types=1);

namespace App\Enums;

enum SubscriptionStatus: string
{
    case Trialing = 'trialing';
    case Active = 'active';
    case PastDue = 'past_due';
    case Cancelled = 'cancelled';
    case Expired = 'expired';

    public function canTransitionTo(self $next): bool
    {
        return in_array($next, $this->allowedNext(), true);
    }

    /** @return list<self> */
    private function allowedNext(): array
    {
        return match ($this) {
            self::Trialing => [self::Active, self::Cancelled, self::Expired],
            self::Active => [self::PastDue, self::Cancelled],
            self::PastDue => [self::Active, self::Cancelled, self::Expired],
            self::Cancelled => [self::Active, self::Expired],
            self::Expired => [self::Active],
        };
    }
}
