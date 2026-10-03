# Multi-Tenant SaaS API

A backend API for a multi-tenant SaaS product, built with Laravel 13. Companies (tenants) sign up, get a subscription plan with feature limits, and manage their own staff users and their own customers. Each company sees usage against its plan and a dashboard, and a platform admin manages plans and companies.

## Stack

- PHP 8.4, Laravel 13
- MySQL 8.4 (application and test databases)
- Redis 7 (cache, queues, rate limits)
- Laravel Sanctum 4 (API tokens)
- spatie/laravel-permission 8 (roles in teams mode, one team per company)
- knuckleswtf/scribe 5 (HTML docs, Postman collection, OpenAPI spec)
- Pest 4 and Laravel Pint
- Docker Compose: `app` (PHP-FPM), `nginx`, `mysql`, `redis`, `queue`, `scheduler`, `mailpit`

## Quick start (Docker)

```bash
git clone <repo> && cd <repo>
cp .env.example .env
docker compose up -d --build
docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate --seed
docker compose restart queue scheduler
```

Then:

- API: `http://localhost:8000/api/v1`
- API docs: `http://localhost:8000/docs`
- Mailpit (all outgoing email): `http://localhost:8025`

The `queue` and `scheduler` containers restart in a loop until `composer install` has run. The last command restarts them once, so they read the new `APP_KEY`.

The PHP containers run as uid 1000, the usual first user on Linux, so files they write in the project folder (`vendor/`, `storage/`) belong to you.

## Database setup and migrations

`php artisan migrate --seed` creates the schema and runs three seeders:

- `PlanSeeder`: the Free, Starter, and Pro plans with their features. It always runs, also in tests.
- `RolePermissionSeeder`: 14 permissions and the owner, admin, and member roles. It always runs, also in tests.
- `DemoSeeder`: three demo companies, their users, 255 customers, and 30 days of usage history. It runs only when `APP_ENV=local`. It uses the same services as the API, so the demo data follows every business rule.

To reset everything: `docker compose exec app php artisan migrate:fresh --seed`.

MySQL is not published to your machine, so it never clashes with a local MySQL on port 3306. To open a MySQL shell: `docker compose exec mysql mysql -usaas -psecret saas`.

Tests use a separate database, `saas_testing`. `docker/mysql/init.sql` creates it the first time the MySQL container starts.

## Demo accounts

Every password is `password`.

| Email | Company | Plan and status | Role |
|---|---|---|---|
| admin@platform.test | none | none | Platform admin |
| owner@acme.test | Acme Ltd | Pro, active | owner |
| admin@acme.test | Acme Ltd | Pro, active | admin |
| member@acme.test | Acme Ltd | Pro, active | member |
| owner@globex.test | Globex | Free, active | owner |
| owner@initech.test | Initech | Starter, trialing | owner |

Acme has 200 customers and a 30-day trend. Globex has 40 customers and Initech has 15.



## Running tests

```bash
docker compose exec app ./vendor/bin/pest
```

The suite has 251 tests on MySQL. It covers tenant isolation, roles, plan limits, the subscription lifecycle, caching and invalidation, rate limits, exports, and the error format. Redis must be running, because the rate limiter uses it directly.

Code style: `docker compose exec app ./vendor/bin/pint`.

## API documentation

- HTML docs (Scribe): `http://localhost:8000/docs`
- Postman collection: [`docs/api/collection.json`](docs/api/collection.json)
- OpenAPI spec: [`docs/api/openapi.yaml`](docs/api/openapi.yaml)
- Sample requests and responses, and the error codes: [`docs/api.md`](docs/api.md)

To regenerate after changing an endpoint: `docker compose exec app php artisan scribe:generate`, then copy `storage/app/private/scribe/collection.json` and `openapi.yaml` into `docs/api/`.

## Key implementation notes

- **Tenant isolation.** The tenant comes only from the logged-in user. A global scope adds `tenant_id` to every query, and a query with no tenant throws. See [system design](docs/system-design.md#multi-tenancy).
- **ULIDs.** Tables use `BIGINT` keys inside the database and expose a `ulid` column in URLs and JSON. See [database](docs/database.md#why-bigint-plus-ulid).
- **Plan limits with row locks.** User and customer creates lock the company row, count, then insert, so parallel requests cannot pass the limit. See [system design](docs/system-design.md#plan-limits-and-the-concurrency-guard).
- **Versioned cache.** Every cache key carries a version number, and one Redis `INCR` invalidates a whole company. See [caching](docs/caching.md).
- **Events after commit.** Services dispatch domain events that fire only after the transaction commits. Listeners clear the cache and queue emails. See [caching](docs/caching.md#after-commit-dispatch).
- **Queues.** Emails, CSV exports, the hourly subscription lifecycle, and the daily usage snapshot run on Redis queues with three priorities. See [system design](docs/system-design.md#background-jobs).
- **Rate limits.** Per plan and per user for the API, and per email and IP for login. See [system design](docs/system-design.md#rate-limiting).
- **Soft deletes and unique emails.** A generated column keeps email unique among non-deleted rows only, so a deleted email can be used again. See [database](docs/database.md#soft-deletes-and-the-generated-email-column).
- **Layers.** Controller, service, repository, with an interface for every repository. See [architecture](docs/architecture.md).
- **Indexes.** Every index and the query it serves. See [optimization](docs/optimization.md).

## Project structure

```
app/
  Contracts/      FeatureGate interface
  DTOs/           Typed, read-only input objects passed from requests to services
  Enums/          Statuses, roles, permissions, feature keys
  Events/         Domain events, plus the two marker interfaces for cache invalidation
  Exceptions/     ApiException, one class per business error, and the JSON error renderer
  Http/           Controllers, middleware, form requests, API resources
  Jobs/           CSV export, subscription lifecycle, daily usage snapshot
  Listeners/      Cache invalidation and email sending
  Mail/           Welcome and set-password emails
  Models/         Eloquent models: relations, casts, small state checks
  Policies/       Authorization per resource
  Providers/      Bindings, listeners, rate limiters, repository bindings
  Repositories/   Contracts/ (interfaces) and Eloquent/ (implementations): every query lives here
  Services/       Business rules, transactions, limit checks, cache
  Support/        Tenancy (context, scope, trait), versioned cache, public ULID trait
```

## Assumptions

The assignment left these open. I chose:

- **The tenant comes from the user.** Each user belongs to one company, and the API reads the company from the logged-in user. There are no subdomains or tenant headers.
- **One company per user.** A person who works for two companies needs two accounts.
- **No payment gateway.** `POST /subscription/renew` stands in for a successful payment.
- **Downgrades over a limit are allowed.** Nothing is deleted. New users or customers are blocked until usage drops below the new limit, and `over_limit` shows it.
- **User email is unique across the platform.** Login takes only email and password, so the email must identify one user.
- **Customers never log in.** They are records a company manages.

## Not included

These were left out on purpose:

- Real payments, invoices, proration, and taxes
- Email verification, two-factor login, and OAuth login
- Subdomain or custom-domain tenant routing
- A user who belongs to more than one company
- A frontend of any kind
- An audit log beyond `subscription_events`

With more time, I would add payment gateway webhooks, a general audit log, Laravel Horizon for queue monitoring, per-company API keys, and cursor pagination for very large lists. See [system design](docs/system-design.md#next-steps).
