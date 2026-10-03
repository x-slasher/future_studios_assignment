<?php

declare(strict_types=1);

namespace App\Exceptions;

final class InvalidCredentials extends ApiException
{
    public function __construct()
    {
        parent::__construct('These credentials do not match our records.');
    }

    public function errorCode(): string
    {
        return 'INVALID_CREDENTIALS';
    }

    public function status(): int
    {
        return 401;
    }
}
