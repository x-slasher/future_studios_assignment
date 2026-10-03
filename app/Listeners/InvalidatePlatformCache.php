<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\Contracts\PlatformDataChanged;
use App\Support\Cache\CacheScope;
use App\Support\Cache\VersionedCache;

final class InvalidatePlatformCache
{
    public function __construct(private readonly VersionedCache $cache) {}

    public function handle(PlatformDataChanged $event): void
    {
        $this->cache->invalidate(CacheScope::platform());
    }
}
