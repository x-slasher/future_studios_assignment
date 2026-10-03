<?php

declare(strict_types=1);

namespace App\Support\Cache;

final class CacheScope
{
    public static function tenant(int $tenantId): string
    {
        return 'tenant:'.$tenantId;
    }

    public static function platform(): string
    {
        return 'platform';
    }
}
