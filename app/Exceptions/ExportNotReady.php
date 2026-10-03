<?php

declare(strict_types=1);

namespace App\Exceptions;

final class ExportNotReady extends ApiException
{
    public function __construct()
    {
        parent::__construct('The export is not ready yet.');
    }

    public function errorCode(): string
    {
        return 'EXPORT_NOT_READY';
    }

    public function status(): int
    {
        return 409;
    }
}
