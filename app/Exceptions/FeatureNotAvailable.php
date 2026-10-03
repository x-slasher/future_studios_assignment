<?php

declare(strict_types=1);

namespace App\Exceptions;

use App\Enums\FeatureKey;

final class FeatureNotAvailable extends ApiException
{
    public function __construct(private readonly FeatureKey $feature)
    {
        parent::__construct('Your plan does not include this feature. Upgrade your plan to use it.');
    }

    public function errorCode(): string
    {
        return 'FEATURE_NOT_AVAILABLE';
    }

    public function status(): int
    {
        return 403;
    }

    public function details(): array
    {
        return ['feature' => $this->feature->value];
    }
}
