<?php

declare(strict_types=1);

namespace App\Exceptions;

final class SubscriptionInactive extends ApiException
{
    public function __construct()
    {
        parent::__construct('Your subscription is not active. Renew it to make changes.');
    }

    public function errorCode(): string
    {
        return 'SUBSCRIPTION_INACTIVE';
    }

    public function status(): int
    {
        return 402;
    }
}
