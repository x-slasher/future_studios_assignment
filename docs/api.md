# API

This page gives the conventions, the error codes, and one sample request and response for the main flows. The full list of 35 endpoints is in the Scribe docs at `http://localhost:8000/docs`. The same endpoints are in the Postman collection ([`api/collection.json`](api/collection.json)) and the OpenAPI spec ([`api/openapi.yaml`](api/openapi.yaml)).

## Conventions

- **Base URL:** `http://localhost:8000/api/v1`
- **Headers:** every request sends `Accept: application/json`. Protected endpoints add `Authorization: Bearer <token>`.
- **Tokens** come from `POST /auth/register` or `POST /auth/login` and expire after 7 days.
- **IDs** are ULIDs, for example `01m3ypws0r7ercmyaecr9y5a12`. Numeric IDs and `tenant_id` never appear.
- **Dates** are ISO 8601 in UTC, for example `2026-10-02T16:24:25Z`.
- **Lists** take `page`, `per_page` (1 to 100, default 15), `sort` (a `-` prefix means descending), and `filter[...]`. Unknown sort fields or filter keys return 422. List responses carry Laravel's `links` and `meta` blocks.
- **Search** (`filter[search]`) is a prefix match: `kar` finds "Karim" but not "Bakar".

In the OpenAPI file, nested query parameters appear in Scribe's dot notation (`filter.status`). Send them as `filter[status]`, as the HTML docs and curl examples show.

## Error format

Every error, from validation to a server fault, has one shape:

```json
{
  "error": {
    "code": "PLAN_LIMIT_EXCEEDED",
    "message": "Your plan allows 5 users. Upgrade your plan to add more.",
    "details": { "feature": "max_users", "limit": 5, "current": 5 }
  }
}
```

`details` is always an object. It is empty when there is nothing to add.

| HTTP | code | When |
|---|---|---|
| 401 | `UNAUTHENTICATED` | Missing, invalid, or expired token |
| 401 | `INVALID_CREDENTIALS` | Login failed (unknown email, wrong password, or inactive user) |
| 402 | `SUBSCRIPTION_INACTIVE` | A write to users, customers, or exports while the subscription is not usable |
| 403 | `FORBIDDEN` | Policy denied, or the wrong route group for the user type |
| 403 | `TENANT_SUSPENDED` | The company is suspended |
| 403 | `PLAN_LIMIT_EXCEEDED` | A quantity limit is reached (`max_users`, `max_customers`) |
| 403 | `FEATURE_NOT_AVAILABLE` | The plan lacks an on/off feature (`customer_export`) |
| 404 | `NOT_FOUND` | The record is missing or belongs to another company. The message never names the model |
| 409 | `INVALID_SUBSCRIPTION_TRANSITION` | The subscription change is not allowed from the current state |
| 409 | `LAST_OWNER` | The change would leave the company without an active owner |
| 409 | `EXPORT_NOT_READY` | Download before the export has completed |
| 422 | `VALIDATION_FAILED` | Invalid input, or a duplicate email. `details` holds the field errors |
| 429 | `RATE_LIMITED` | Rate limit hit. `Retry-After` and `X-RateLimit-*` headers are set |
| 500 | `SERVER_ERROR` | Anything unexpected. A generic message when debug is off |

Other HTTP errors keep their status with the code `HTTP_ERROR`, for example `405` when a route exists but not for that method.

## Register

```http
POST /api/v1/auth/register
Content-Type: application/json
Accept: application/json

{
  "company_name": "Rahim Traders",
  "name": "Rahim Uddin",
  "email": "rahim@rahimtraders.test",
  "password": "Secret123",
  "password_confirmation": "Secret123",
  "plan_code": "starter"
}
```

```json
201 Created
{
  "data": {
    "token": "1|example-token",
    "user": {
      "id": "01m3yq2m67tyfq22zt14a0ymyq", "name": "Rahim Uddin", "email": "rahim@rahimtraders.test",
      "role": "owner", "is_active": true, "last_login_at": null, "created_at": "2026-10-02T16:27:37Z",
      "tenant": { "id": "01m3yq2kzkqwbq7wbddstwev0v", "name": "Rahim Traders", "status": "active", "created_at": "2026-10-02T16:27:36Z" }
    },
    "tenant": { "id": "01m3yq2kzkqwbq7wbddstwev0v", "name": "Rahim Traders", "status": "active", "created_at": "2026-10-02T16:27:36Z" },
    "subscription": {
      "id": "01m3yq2m6ch3k7tjxzbr2528rx", "status": "trialing",
      "plan": { "id": "01m3ypwrg7s3rw8thb4tw1226n", "code": "starter", "name": "Starter", "price_cents": 1900, "currency": "USD", "features": ["..."] },
      "trial_ends_at": "2026-10-16T16:27:37Z", "current_period_start": null, "current_period_end": null,
      "cancelled_at": null, "ends_at": null, "is_usable": true
    }
  }
}
```

A paid plan starts a 14-day trial. `plan_code` is optional and defaults to `starter`. With `"plan_code": "free"`, the subscription starts `active` with no period.

## Login

```http
POST /api/v1/auth/login

{ "email": "owner@acme.test", "password": "password" }
```

```json
200 OK
{
  "data": {
    "token": "1|example-token",
    "user": {
      "id": "01m3ypws7j18e4984mfb5kkz73", "name": "Acme Ltd Owner", "email": "owner@acme.test", "role": "owner",
      "is_active": true, "last_login_at": "2026-10-02T16:27:37Z", "created_at": "2026-10-02T16:24:25Z",
      "tenant": { "id": "01m3ypws0r7ercmyaecr9y5a12", "name": "Acme Ltd", "status": "active", "created_at": "2026-10-02T16:24:25Z" }
    }
  }
}
```

## Create a customer

```http
POST /api/v1/customers
Authorization: Bearer 1|example-token

{ "name": "Karim Store", "email": "karim@store.test", "phone": "+8801700000000", "company_name": "Karim Ltd" }
```

```json
201 Created
{
  "data": {
    "id": "01m3yq2mys56kee6prc9xsn2ac", "name": "Karim Store", "email": "karim@store.test",
    "phone": "+8801700000000", "company_name": "Karim Ltd", "status": "active",
    "created_by": { "id": "01m3ypws7j18e4984mfb5kkz73", "name": "Acme Ltd Owner" },
    "created_at": "2026-10-02T16:27:37Z", "updated_at": "2026-10-02T16:27:37Z"
  }
}
```

## List customers with filters

```http
GET /api/v1/customers?filter[status]=active&filter[search]=customer1&filter[created_from]=2026-09-01&sort=name&per_page=2
Authorization: Bearer 1|example-token
```

```json
200 OK
{
  "data": [
    { "id": "01m3ypwxdnyr78pps2afa67jna", "name": "Aliya Schaefer", "email": "customer196@acme-ltd.test", "phone": "+8801096905918", "company_name": null, "status": "active",
      "created_by": { "id": "01m3ypws7j18e4984mfb5kkz73", "name": "Acme Ltd Owner" }, "created_at": "2026-09-10T13:08:29Z", "updated_at": "2026-09-10T13:08:29Z" },
    { "id": "01m3ypwwk4h27qfd47beb1cp1c", "name": "Arnold Torp V", "email": "customer145@acme-ltd.test", "phone": "+8801910411792", "company_name": "Tremblay and Sons", "status": "active",
      "created_by": { "id": "01m3ypws7j18e4984mfb5kkz73", "name": "Acme Ltd Owner" }, "created_at": "2026-09-07T13:59:29Z", "updated_at": "2026-09-07T13:59:29Z" }
  ],
  "links": {
    "first": "http://localhost:8000/api/v1/customers?filter%5Bstatus%5D=active&...&page=1",
    "last": "http://localhost:8000/api/v1/customers?filter%5Bstatus%5D=active&...&page=20",
    "prev": null,
    "next": "http://localhost:8000/api/v1/customers?filter%5Bstatus%5D=active&...&page=2"
  },
  "meta": { "current_page": 1, "from": 1, "last_page": 20, "path": "http://localhost:8000/api/v1/customers", "per_page": 2, "to": 2, "total": 39 }
}
```

The `links` keep every filter and the sort, so a client can follow `next` as it is. `meta` also carries Laravel's page `links` list, left out here.

Filters: `status` (`active`, `inactive`), `search` (prefix on name or email), `created_from` and `created_to` (`Y-m-d`, whole days, inclusive). Sorts: `-created_at` (default), `created_at`, `name`, `-name`.

## Plan limit error

The Globex owner is on Free (2 users) and already has 2 users:

```http
POST /api/v1/users

{ "name": "Two", "email": "two@globex.test", "role": "member" }
```

```json
403 Forbidden
{
  "error": {
    "code": "PLAN_LIMIT_EXCEEDED",
    "message": "Your plan allows 2 users. Upgrade your plan to add more.",
    "details": { "feature": "max_users", "limit": 2, "current": 2 }
  }
}
```

## Dashboard

```http
GET /api/v1/dashboard
```

```json
200 OK
{
  "data": {
    "subscription": { "status": "active", "plan": "pro", "current_period_end": "2026-11-02T16:24:25Z", "days_left": 31 },
    "usage": {
      "users": { "used": 3, "limit": 25, "over_limit": false },
      "customers": { "used": 200, "limit": 5000, "over_limit": false }
    },
    "customers": { "total": 200, "active": 180, "inactive": 20, "new_last_30_days": 69 },
    "trend": [
      { "date": "2026-09-03", "users": 3, "customers": 156 },
      { "date": "2026-09-04", "users": 3, "customers": 158 }
    ]
  }
}
```

`trend` covers the last 30 days and is `null` when the plan lacks `analytics_trends` (Free and Starter). `limit: null` means unlimited, and then `over_limit` is always `false`. `days_left` counts to the trial end, the cancellation end, or the period end, and is `null` on the Free plan.

## Admin dashboard

```http
GET /api/v1/admin/dashboard
Authorization: Bearer <platform admin token>
```

```json
200 OK
{
  "data": {
    "tenants": { "total": 3, "active": 3, "suspended": 0, "new_last_30_days": 3 },
    "subscriptions_by_status": { "trialing": 1, "active": 2, "past_due": 0, "cancelled": 0, "expired": 0 },
    "subscriptions_by_plan": { "free": 1, "starter": 1, "pro": 1 },
    "mrr_cents": 4900,
    "churned_last_30_days": 0
  }
}
```

MRR counts subscriptions with status `active` or `past_due` on paid plans. Churn counts `cancelled` and `expired` subscription events in the last 30 days.
