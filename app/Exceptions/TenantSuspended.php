<?php

declare(strict_types=1);

namespace App\Exceptions;

final class TenantSuspended extends ApiException
{
    public function __construct()
    {
        parent::__construct('Your company account is suspended.');
    }

    public function errorCode(): string
    {
        return 'TENANT_SUSPENDED';
    }

    public function status(): int
    {
        return 403;
    }
}
