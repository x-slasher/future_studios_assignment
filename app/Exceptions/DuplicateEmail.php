<?php

declare(strict_types=1);

namespace App\Exceptions;

final class DuplicateEmail extends ApiException
{
    private const string MESSAGE = 'The email has already been taken.';

    public function __construct()
    {
        parent::__construct(self::MESSAGE);
    }

    public function errorCode(): string
    {
        return 'VALIDATION_FAILED';
    }

    public function status(): int
    {
        return 422;
    }

    public function details(): array
    {
        return ['email' => [self::MESSAGE]];
    }
}
