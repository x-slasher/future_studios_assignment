<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\Contracts\TenantDataChanged;
use App\Support\Cache\CacheScope;
use App\Support\Cache\VersionedCache;

final class InvalidateTenantCache
{
    public function __construct(private readonly VersionedCache $cache) {}

    public function handle(TenantDataChanged $event): void
    {
        $this->cache->invalidate(CacheScope::tenant($event->tenantId()));
    }
}
