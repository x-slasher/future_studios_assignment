<?php

declare(strict_types=1);

namespace App\Exceptions;

final class LastOwnerException extends ApiException
{
    public function __construct()
    {
        parent::__construct('A company must keep at least one active owner.');
    }

    public function errorCode(): string
    {
        return 'LAST_OWNER';
    }

    public function status(): int
    {
        return 409;
    }
}
