# Caching

Redis is the cache store. Every cached value lives under a **scope** (one company, or the whole platform), and each scope has a version number. Invalidation increments the version.

## What is cached

| Data | Scope | Key name | TTL | Built by | Why cache it |
|---|---|---|---|---|---|
| Company dashboard | company | `dashboard` | 600s | `TenantDashboardService` | Eight queries, including grouped counts over all customers, on a screen people refresh often |
| Company plan features | company | `features:pv{platformVersion}` | 3600s | `PlanFeatureGate` | Read on almost every request: the rate limiter, limit checks, and feature middleware |
| Active plan list | platform | `plans:active` | 3600s | `PlanService::activeForDisplay()` | Public, the same for everyone, and rarely changed. The finished JSON array is cached, so it is a true response cache |
| Platform dashboard | platform | `dashboard` | 300s | `PlatformDashboardService` | Grouped counts across every company |

Only plain arrays are cached, never Eloquent models. A cached model carries stale relations and breaks when the class changes.

## What is not cached, and why

| Data | Reason |
|---|---|
| Customer and user lists | Many filter, sort, and page combinations, so the hit rate would be low. Each list query is served by a composite index and is already fast. Writes are frequent and would keep invalidating it |
| One record by ULID | A unique index lookup of one row |
| `GET /subscription` | One indexed row and two indexed counts |
| Auth and permission checks | The Sanctum token lookup is indexed. spatie caches role and permission definitions itself |

## The versioned key scheme

```
tenant:42:version            -> 7            (no TTL)
tenant:42:v7:dashboard       -> {...}        (TTL 600s)
tenant:42:v7:features:pv3    -> {...}        (TTL 3600s)
platform:version             -> 3
platform:v3:plans:active     -> [...]        (TTL 3600s)
platform:v3:dashboard        -> {...}        (TTL 300s)
```

`VersionedCache` builds each key from the scope, the scope's current version, and the name:

```php
public function remember(string $scope, string $name, int $ttlSeconds, Closure $callback): mixed
{
    return $this->cache->remember($this->key($scope, $name), $ttlSeconds, $callback);
}

public function invalidate(string $scope): void
{
    $this->cache->increment($this->versionKey($scope)); // one Redis INCR
}
```

After an `INCR`, every read builds a key with the new version number. The old keys are never read again.

## Why versioned keys

The two common alternatives were cache tags, and deleting known keys:

- **O(1) invalidation.** One `INCR` clears a whole company, however many keys it has. Deleting keys would mean finding them first.
- **No tag sets to maintain.** Laravel cache tags keep a Redis set per tag, and those sets need their own cleanup.
- **New cached items need no invalidation code.** A new `remember()` call under the company scope is cleared by the same events automatically.

## Invalidation

| Event | Bumps |
|---|---|
| `CustomerCreated`, `CustomerUpdated`, `CustomerDeleted` | company |
| `UserCreated`, `UserUpdated`, `UserDeleted` | company |
| `TenantUpdated` | company |
| `SubscriptionChanged` | company and platform |
| `TenantRegistered`, `TenantStatusChanged` | platform |
| `PlanChanged` | platform |

Events implement one of two marker interfaces, `TenantDataChanged` or `PlatformDataChanged` (`SubscriptionChanged` implements both). `AppServiceProvider` registers one listener per interface:

```php
Event::listen(TenantDataChanged::class, InvalidateTenantCache::class);
Event::listen(PlatformDataChanged::class, InvalidatePlatformCache::class);
```

Laravel calls listeners registered for an interface on every event that implements it. So a new event invalidates the cache by implementing the interface, with no change to the listeners. Both listeners run synchronously, not on a queue, because the next request must see fresh data.

Daily snapshots do not bump the version. The dashboard TTL is 10 minutes, so the new trend point shows within 10 minutes of the nightly job.

## After-commit dispatch

Every event implements `ShouldDispatchAfterCommit`. Laravel holds the event until the database transaction commits, and drops it if the transaction rolls back.

- **A rolled-back write never clears the cache.** No version is bumped for data that does not exist.
- **No stale value from an uncommitted write.** If the cache were cleared before the commit, a parallel request could rebuild it from the old, committed data, and that stale value would then stay for the full TTL.

`CacheInvalidationTest` checks this: a customer create inside a transaction that throws leaves the version unchanged.

## How a plan edit refreshes every company

Each company caches its own copy of its plan's features. When an admin edits a plan, every company on that plan needs fresh features, and there could be thousands of them.

The feature key includes the platform version: `tenant:42:v7:features:pv3`. `PlanChanged` bumps only the platform version (3 to 4). On its next request, each company builds the key `features:pv4`, misses, and reads the new limits. There is no loop over companies. `AdminPlanTest` checks that a limit edited by an admin applies to a company on its next request.

## Known trade-off

Old keys stay in Redis until their TTL runs out. Memory is bounded: at most one dashboard (10 minutes) and one feature map (1 hour) per company per version change, all with a TTL. If memory ever became tight, the TTLs could be shortened, or Redis could run with an `allkeys-lru` eviction policy on the cache database.

The cache lives in Redis database 1 and the queues in database 0, Laravel's default split. `cache:clear` never deletes queued jobs.
