<?php

declare(strict_types=1);

use App\Support\Cache\CacheScope;
use App\Support\Cache\VersionedCache;
use Illuminate\Cache\ArrayStore;
use Illuminate\Cache\Repository;

beforeEach(function (): void {
    $this->cache = new VersionedCache(new Repository(new ArrayStore));
});

it('remembers a value until the scope is invalidated', function (): void {
    $calls = 0;
    $build = function () use (&$calls): int {
        return ++$calls;
    };

    $first = $this->cache->remember('tenant:1', 'dashboard', 60, $build);
    $second = $this->cache->remember('tenant:1', 'dashboard', 60, $build);

    expect($first)->toBe(1)->and($second)->toBe(1)->and($calls)->toBe(1);

    $this->cache->invalidate('tenant:1');

    expect($this->cache->remember('tenant:1', 'dashboard', 60, $build))->toBe(2);
});

it('starts at version 0 and increments by one per invalidation', function (): void {
    expect($this->cache->version('platform'))->toBe(0);

    $this->cache->invalidate('platform');
    $this->cache->invalidate('platform');

    expect($this->cache->version('platform'))->toBe(2);
});

it('keeps scopes independent', function (): void {
    $this->cache->remember(CacheScope::tenant(1), 'dashboard', 60, fn (): string => 'tenant one');
    $this->cache->remember(CacheScope::tenant(2), 'dashboard', 60, fn (): string => 'tenant two');

    $this->cache->invalidate(CacheScope::tenant(1));

    expect($this->cache->version(CacheScope::tenant(2)))->toBe(0)
        ->and($this->cache->remember(CacheScope::tenant(2), 'dashboard', 60, fn (): string => 'rebuilt'))->toBe('tenant two')
        ->and($this->cache->remember(CacheScope::tenant(1), 'dashboard', 60, fn (): string => 'rebuilt'))->toBe('rebuilt');
});

it('builds scope names', function (): void {
    expect(CacheScope::tenant(42))->toBe('tenant:42')
        ->and(CacheScope::platform())->toBe('platform');
});
