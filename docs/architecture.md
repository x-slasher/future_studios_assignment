# Architecture

The code uses three layers, **Controller, Service, Repository**, on Laravel's standard folder layout.

## Folder layout and why this pattern

```
app/
  Contracts/  DTOs/  Enums/  Events/  Exceptions/  Http/  Jobs/  Listeners/
  Mail/  Models/  Policies/  Providers/  Repositories/  Services/  Support/
```

I chose a service and repository layer over domain folders (`app/Domain/...`) or one Action class per use case:

- **Fewer files.** One `CustomerService` holds create, update, delete, and list, instead of four Action classes.
- **Familiar.** Any Laravel developer finds things where they expect them.
- **Matches the assignment.** It asks for service classes and repositories.
- **Enough separation for this size.** The API has 35 endpoints and nine domain tables. Domain folders would add structure without solving a real problem here.

## Request flow

```
HTTP request
  -> Middleware        auth, tenant context, rate limit, subscription and feature checks
  -> Controller        authorize, FormRequest -> DTO, call one service method, return a Resource
  -> Service           business rules, transaction, row lock, limit check, dispatch events, cache
  -> Repository        all Eloquent queries and writes
  -> Model             relations, casts, small state checks
  -> after commit:     events -> listeners (cache version bump, queued emails)
```

## Layer rules

| Layer | Does | Never does |
|---|---|---|
| Controller | `Gate::authorize()`, `$request->toDto()`, call one service method, return a Resource or `204` | Query the database, call a repository, hold business rules |
| FormRequest | Validation rules, `toDto()` / `toFilter()`, Scribe parameter docs | Authorization, queries other than `Rule::unique` / `Rule::exists` |
| DTO | Carry typed input (`final readonly class`) | Logic |
| Service | Business rules, `DB::transaction()`, limit checks, status changes, events, cache, repository calls | Build Eloquent queries, read `Request`, return a `Response` |
| Repository | Every Eloquent query and write, filters, sorting, eager loading, row locks | Business rules, events, cache, transactions |
| Model | Relations, casts, `$fillable`, small state checks such as `Subscription::isUsable()` | Workflows |
| Resource | Shape JSON, hide internal IDs | Queries (relations are eager loaded by the repository) |
| Job | Call one service method with the IDs it was given | Business logic, repository calls |
| Listener | One side effect: bump a cache version, or send an email | Business rules |

Two places may query outside a repository, and both are deliberate:

- **Route model binding** (`/customers/{customer}`). It goes through the tenant global scope, so another company's ULID returns 404. `Customer` also eager loads its creator during binding, so the response needs no lazy load.
- **Validation rules** `Rule::unique()` and `Rule::exists()`.

`Model::shouldBeStrict()` is on outside production. A lazy load, a mass assignment of an attribute not in `$fillable`, or a read of a missing attribute all throw, so an N+1 query fails a test instead of reaching production.

## Patterns used

| Pattern | Where | Why |
|---|---|---|
| Service layer | `app/Services` | Business rules live in one place. Controllers, jobs, and the demo seeder all call the same code |
| Repository | `app/Repositories` | All data access in one layer. One filter serves both the customer list and the CSV export |
| DTO | `app/DTOs` | Services never see `Request`, so they run the same from HTTP, jobs, and seeders |
| Global scope | `TenantScope` | The tenant filter is added to every query automatically, not remembered by hand |
| Context object | `TenantContext`, a scoped binding | One source of truth for the current company, in requests and in jobs |
| Events and listeners (observer) | `app/Events`, `app/Listeners` | A new side effect needs a new listener, not a change to the service |
| State machine (enum) | `SubscriptionStatus::canTransitionTo()` | Invalid status changes are rejected in one place |
| Middleware chain | `app/Http/Middleware` | Cross-cutting checks run before any controller code |
| Presenter | API Resources | One place shapes JSON and hides internal IDs |

## Repository design

There is one repository per main table, plus `UsageRepository` for counts and snapshots. A child table belongs to its parent's repository: `plan_features` to `PlanRepository`, `subscription_events` to `SubscriptionRepository`, and `daily_usage_snapshots` to `UsageRepository`.

- **Every repository has an interface.** The seven interfaces live in `app/Repositories/Contracts`, the `final` implementations in `app/Repositories/Eloquent`. `RepositoryServiceProvider` binds them in one `$bindings` array. Services type-hint only the interface. `RepositoryBindingTest` checks that each interface resolves to its class, and that each class has exactly the public methods of its interface.
- **No `BaseRepository`.** Generic `find`, `all`, `create`, and `update` methods hide intent and let services build ad-hoc queries again. Each repository has only the methods its callers need, named for what they return: `paginate(CustomerFilter)`, `findLocked(int $id)`, `activeWithFeatures()`.
- **Cross-tenant reads are easy to find.** A method that reads across companies ends in `AcrossTenants`, and it is the only place `withoutGlobalScope(TenantScope::class)` appears. Only admin services and scheduled jobs call these methods. `grep -rn AcrossTenants app` shows every cross-company read in the code.

**The honest limit.** The interfaces return Eloquent models and paginators. Moving to a non-Eloquent store would still touch the callers. The gain is a clear contract per service and fakes in unit tests (`FeatureGateTest` mocks `PlanRepositoryInterface`), not storage independence.

## SOLID, applied

| Principle | In this code |
|---|---|
| Single responsibility | The controller handles HTTP, the FormRequest validates, the service holds rules, the repository holds queries, the Resource formats output, and each listener does one thing. `CustomerExportService` is separate from `CustomerService` because exporting changes for different reasons than CRUD |
| Open/closed | A new business error is a new `ApiException` subclass; `ApiExceptionRenderer` does not change. A new side effect is a new listener. A new plan feature is a `FeatureKey` case and a seed row; `PlanFeatureGate` does not change |
| Liskov substitution | Every `ApiException` subclass renders through the same renderer. Any `FeatureGate` implementation, real or a test double, works in every service |
| Interface segregation | Seven small repository interfaces, each with only the methods its services use, instead of one generic CRUD interface. `FeatureGate` has four methods, and all four are used |
| Dependency inversion | Services depend on the seven repository interfaces (bound in `RepositoryServiceProvider`) and on `FeatureGate` (bound in `AppServiceProvider`). `VersionedCache` depends on Laravel's `Cache\Repository` contract |

## How the tenant context reaches jobs

`TenantContext` is a scoped binding. Laravel flushes scoped instances between requests and between queued jobs, so one job's company can never leak into the next.

A job receives only IDs, for example `ExportCustomersJob(int $tenantId, int $exportId)`. It calls one service method, and the service does the rest:

```php
$tenant = $this->tenants->findById($tenantId);

$this->context->run($tenant, function () use ($exportId): void {
    $export = $this->exports->findById($exportId); // tenant-scoped, like in a request
    // ...
});
```

`TenantContext::run()` sets the company, runs the callback, and restores the previous context, even when the callback throws. The same method serves the admin tenant detail, the lifecycle job (one call per subscription), and the demo seeder.
