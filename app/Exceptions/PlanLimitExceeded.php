<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Enums\FeatureKey;

final class PlanLimitExceeded extends ApiException
{
    public function __construct(
        private readonly FeatureKey $feature,
        private readonly int $limit,
        private readonly int $current,
    ) {
        $noun = match ($feature) {
            FeatureKey::MaxUsers => 'users',
            FeatureKey::MaxCustomers => 'customers',
            default => $feature->value,
        };

        parent::__construct("Your plan allows {$limit} {$noun}. Upgrade your plan to add more.");
    }

    public function errorCode(): string
    {
        return 'PLAN_LIMIT_EXCEEDED';
    }

    public function status(): int
    {
        return 403;
    }

    public function details(): array
    {
        return [
            'feature' => $this->feature->value,
            'limit' => $this->limit,
            'current' => $this->current,
        ];
    }
}
