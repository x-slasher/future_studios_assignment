<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

abstract class ApiException extends Exception
{
    abstract public function errorCode(): string;

    abstract public function status(): int;

    /** @return array<string, mixed> */
    public function details(): array
    {
        return [];
    }
}
