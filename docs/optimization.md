# Database optimization and indexing

Every index below exists for a specific query. Each query is written to use its index.

## Index table

| Table | Index | Query it serves |
|---|---|---|
| tenants | `UNIQUE (ulid)` | Admin routes `/admin/tenants/{tenant}` |
| tenants | `(status, created_at)` | Admin list filtered by status, newest first. Platform dashboard counts by status |
| users | `UNIQUE (ulid)` | `/users/{user}` |
| users | `UNIQUE (email_active)` | Login by email. Email uniqueness among non-deleted users |
| users | `(tenant_id, created_at)` | User list per company, newest first. The user count for `max_users` |
| plans | `UNIQUE (ulid)`, `UNIQUE (code)` | `/admin/plans/{plan}`. `plan_code` lookups on register, change-plan, and renew |
| plans | `(is_active, sort_order)` | Public plan list: active plans in display order |
| plan_features | `UNIQUE (plan_id, feature_key)` | Features of a plan. The one-query feature map per company. Stops duplicate features |
| subscriptions | `UNIQUE (tenant_id)` | The current company's subscription, read on most requests |
| subscriptions | `(status, trial_ends_at)` | Lifecycle job: trials that have ended |
| subscriptions | `(status, current_period_end)` | Lifecycle job: paid periods that have ended, and past-due subscriptions past the grace days |
| subscriptions | `(status, ends_at)` | Lifecycle job: cancellations whose access has ended |
| subscriptions | `(plan_id)` | Foreign key. Platform dashboard counts by plan |
| subscription_events | `(tenant_id, occurred_at)` | History per company |
| subscription_events | `(type, occurred_at)` | Platform dashboard churn: cancelled and expired events in the last 30 days |
| customers | `UNIQUE (ulid)` | `/customers/{customer}` |
| customers | `UNIQUE (tenant_id, email_active)` | Email unique per company among non-deleted customers |
| customers | `(tenant_id, status, created_at)` | List filtered by status, newest first. Dashboard counts by status |
| customers | `(tenant_id, created_at)` | List without a status filter. New customers in the last 30 days. The count for `max_customers` |
| customers | `(tenant_id, name)` | Prefix search on name, and sort by name |
| customer_exports | `UNIQUE (ulid)`, `(tenant_id, created_at)` | `/customers/exports/{export}`. Exports per company |
| daily_usage_snapshots | `UNIQUE (tenant_id, snapshot_date)` | Dashboard trend for the last 30 days. Makes the nightly upsert idempotent |

MySQL also creates an index for any foreign key column that does not lead an existing index: `customers.created_by_user_id`, `customer_exports.requested_by_user_id`, and `subscription_events.subscription_id`, `from_plan_id`, and `to_plan_id`. MySQL requires these. The migrations declare each foreign key after the composite indexes, so the `tenant_id` foreign keys reuse the composite indexes and get no extra index.

## Why `tenant_id` leads every composite index

Every company query filters by `tenant_id` first, because the global scope adds it. With `tenant_id` as the first column, each company's rows sit next to each other in the index. A list, count, or sort reads one company's slice and never scans other companies' rows. As the platform grows, a query for a small company stays as fast as when it was the only one.

## Prefix search instead of `%term%`

`filter[search]=kar` becomes `WHERE name LIKE 'kar%' OR email LIKE 'kar%'`. A B-tree index can serve `'kar%'`, because the matches are one contiguous range of the `(tenant_id, name)` index. A leading wildcard (`'%kar%'`) cannot use the index and scans every row of the company.

The term is escaped before binding, so the user cannot add wildcards of their own:

```php
private function prefix(string $term): string
{
    return addcslashes($term, '\\%_').'%';
}
```

A search for `%` or `_` matches literally and returns nothing in the test data (`CustomerListingTest`).

## Eager loading and strict mode

Repositories eager load every relation a Resource uses: `createdBy:id,ulid,name` for customers, `roles` for users, `plan.features` for subscriptions, and `subscription.plan.features` for the admin company list. Only the columns the Resource needs are selected for `createdBy`.

`Model::shouldBeStrict()` is on outside production, so a lazy load throws. A forgotten eager load fails a test instead of becoming an N+1 query in production.

`CustomerListingTest` proves it: the customer list runs the same number of queries for 5 customers and for 50. The 5 customers each have a different creator, so a missing eager load would add queries.

## Grouped counts in the snapshot job

The nightly snapshot needs a user count and a customer count for every company. Two grouped queries cover all companies:

```sql
SELECT tenant_id, COUNT(*) FROM users     WHERE deleted_at IS NULL AND tenant_id IS NOT NULL GROUP BY tenant_id;
SELECT tenant_id, COUNT(*) FROM customers WHERE deleted_at IS NULL GROUP BY tenant_id;
```

That is 2 queries in total instead of 2 per company, so 1,000 companies still cost 2 queries. The rows are then written with `upsert()` in chunks of 500 on the `(tenant_id, snapshot_date)` unique key.

The dashboard also groups: customer counts by status are one `GROUP BY status` query, not one count per status.

## `lazyById()` in the export

The CSV export reads customers with `lazyById(1000)`. It fetches 1,000 rows at a time using `WHERE id > last_id`, which stays fast at any depth, unlike `OFFSET`. Rows are written straight to a temp stream with `fputcsv`, and the stream is copied to disk. Memory stays flat whether the export has 10 rows or 1,000,000.

The hourly lifecycle job uses `lazyById(200)` the same way. It is safe even though the job changes the `status` column it filters on, because each page starts after the last ID seen.

## Pagination limits

Every list takes `per_page` from 1 to 100, with a default of 15. The FormRequest rejects `per_page=101` with 422, so a client cannot ask for a whole table in one request. Sorting always adds `id` as a tie-breaker, so pages are stable when many rows share a `created_at` or a name.

Sort fields are checked twice: against an allowlist in the FormRequest, and again in the repository. A column name from the request never reaches the query unchecked.

## Next step for very large tables

`paginate()` uses `LIMIT ... OFFSET`. Page 1 and page 20 are both fast, but at page 10,000 MySQL still walks past every skipped row. For a company with millions of customers, the next step is cursor pagination (`cursorPaginate()`): it continues from the last `(created_at, id)` seen, so every page costs the same. The trade-off is no "jump to page N" and no total count. That suits an infinite scroll and an API client, but not a numbered page list.
