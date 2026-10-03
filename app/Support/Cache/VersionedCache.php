<?php

declare(strict_types=1);

namespace App\Support\Cache;

use Closure;
use Illuminate\Contracts\Cache\Repository;

final class VersionedCache
{
    public function __construct(private readonly Repository $cache) {}

    public function remember(string $scope, string $name, int $ttlSeconds, Closure $callback): mixed
    {
        return $this->cache->remember($this->key($scope, $name), $ttlSeconds, $callback);
    }

    public function invalidate(string $scope): void
    {
        $this->cache->increment($this->versionKey($scope));
    }

    public function version(string $scope): int
    {
        return (int) $this->cache->get($this->versionKey($scope), 0);
    }

    private function key(string $scope, string $name): string
    {
        return sprintf('%s:v%d:%s', $scope, $this->version($scope), $name);
    }

    private function versionKey(string $scope): string
    {
        return $scope.':version';
    }
}
