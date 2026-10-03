<?php

declare(strict_types=1);

namespace App\Exceptions;

final class InvalidSubscriptionTransition extends ApiException
{
    public function __construct(string $message = 'This subscription change is not allowed.')
    {
        parent::__construct($message);
    }

    public function errorCode(): string
    {
        return 'INVALID_SUBSCRIPTION_TRANSITION';
    }

    public function status(): int
    {
        return 409;
    }
}
