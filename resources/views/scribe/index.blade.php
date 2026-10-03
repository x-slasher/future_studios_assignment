<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>SaaS API Documentation</title>

    <link href="https://fonts.googleapis.com/css?family=Open+Sans&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.style.css") }}" media="screen">
    <link rel="stylesheet" href="{{ asset("/vendor/scribe/css/theme-default.print.css") }}" media="print">

    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.10/lodash.min.js"></script>

    <link rel="stylesheet"
          href="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/styles/obsidian.min.css">
    <script src="https://unpkg.com/@highlightjs/cdn-assets@11.6.0/highlight.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jets/0.14.1/jets.min.js"></script>

    <style id="language-style">
        /* starts out as display none and is replaced with js later  */
                    body .content .bash-example code { display: none; }
            </style>

    <script>
        var tryItOutBaseUrl = "http://localhost:8000";
        var useCsrf = Boolean();
        var csrfUrl = "/sanctum/csrf-cookie";
    </script>
    <script src="{{ asset("/vendor/scribe/js/tryitout-5.11.0.js") }}"></script>

    <script src="{{ asset("/vendor/scribe/js/theme-default-5.11.0.js") }}"></script>

</head>

<body data-languages="[&quot;bash&quot;]">

<a href="#" id="nav-button">
    <span>
        MENU
        <img src="{{ asset("/vendor/scribe/images/navbar.png") }}" alt="navbar-image"/>
    </span>
</a>
<div class="tocify-wrapper">
    
            <div class="lang-selector">
                                            <button type="button" class="lang-button" data-language-name="bash">bash</button>
                    </div>
    
    <div class="search">
        <input type="text" class="search" id="input-search" placeholder="Search">
    </div>

    <div id="toc">
                    <ul id="tocify-header-introduction" class="tocify-header">
                <li class="tocify-item level-1" data-unique="introduction">
                    <a href="#introduction">Introduction</a>
                </li>
                            </ul>
                    <ul id="tocify-header-authenticating-requests" class="tocify-header">
                <li class="tocify-item level-1" data-unique="authenticating-requests">
                    <a href="#authenticating-requests">Authenticating requests</a>
                </li>
                            </ul>
                    <ul id="tocify-header-auth" class="tocify-header">
                <li class="tocify-item level-1" data-unique="auth">
                    <a href="#auth">Auth</a>
                </li>
                                    <ul id="tocify-subheader-auth" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="auth-POSTapi-v1-auth-register">
                                <a href="#auth-POSTapi-v1-auth-register">Register a company</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="auth-POSTapi-v1-auth-login">
                                <a href="#auth-POSTapi-v1-auth-login">Log in</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="auth-POSTapi-v1-auth-forgot-password">
                                <a href="#auth-POSTapi-v1-auth-forgot-password">Send a set-password token</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="auth-POSTapi-v1-auth-reset-password">
                                <a href="#auth-POSTapi-v1-auth-reset-password">Set a new password</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="auth-POSTapi-v1-auth-logout">
                                <a href="#auth-POSTapi-v1-auth-logout">Log out</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="auth-GETapi-v1-auth-me">
                                <a href="#auth-GETapi-v1-auth-me">Current user</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-tenant" class="tocify-header">
                <li class="tocify-item level-1" data-unique="tenant">
                    <a href="#tenant">Tenant</a>
                </li>
                                    <ul id="tocify-subheader-tenant" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="tenant-GETapi-v1-tenant">
                                <a href="#tenant-GETapi-v1-tenant">Show your company</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="tenant-PATCHapi-v1-tenant">
                                <a href="#tenant-PATCHapi-v1-tenant">Rename your company</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-users" class="tocify-header">
                <li class="tocify-item level-1" data-unique="users">
                    <a href="#users">Users</a>
                </li>
                                    <ul id="tocify-subheader-users" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="users-GETapi-v1-users">
                                <a href="#users-GETapi-v1-users">List users</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="users-POSTapi-v1-users">
                                <a href="#users-POSTapi-v1-users">Create a user</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="users-GETapi-v1-users--user_ulid-">
                                <a href="#users-GETapi-v1-users--user_ulid-">Show a user</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="users-PATCHapi-v1-users--user_ulid-">
                                <a href="#users-PATCHapi-v1-users--user_ulid-">Update a user</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="users-DELETEapi-v1-users--user_ulid-">
                                <a href="#users-DELETEapi-v1-users--user_ulid-">Delete a user</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-customers" class="tocify-header">
                <li class="tocify-item level-1" data-unique="customers">
                    <a href="#customers">Customers</a>
                </li>
                                    <ul id="tocify-subheader-customers" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="customers-GETapi-v1-customers">
                                <a href="#customers-GETapi-v1-customers">List customers</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="customers-POSTapi-v1-customers">
                                <a href="#customers-POSTapi-v1-customers">Create a customer</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="customers-GETapi-v1-customers--customer_ulid-">
                                <a href="#customers-GETapi-v1-customers--customer_ulid-">Show a customer</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="customers-PATCHapi-v1-customers--customer_ulid-">
                                <a href="#customers-PATCHapi-v1-customers--customer_ulid-">Update a customer</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="customers-DELETEapi-v1-customers--customer_ulid-">
                                <a href="#customers-DELETEapi-v1-customers--customer_ulid-">Delete a customer</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-customer-exports" class="tocify-header">
                <li class="tocify-item level-1" data-unique="customer-exports">
                    <a href="#customer-exports">Customer Exports</a>
                </li>
                                    <ul id="tocify-subheader-customer-exports" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="customer-exports-POSTapi-v1-customers-exports">
                                <a href="#customer-exports-POSTapi-v1-customers-exports">Request a CSV export</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="customer-exports-GETapi-v1-customers-exports--export_ulid-">
                                <a href="#customer-exports-GETapi-v1-customers-exports--export_ulid-">Show an export</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="customer-exports-GETapi-v1-customers-exports--export_ulid--download">
                                <a href="#customer-exports-GETapi-v1-customers-exports--export_ulid--download">Download an export</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-plans" class="tocify-header">
                <li class="tocify-item level-1" data-unique="plans">
                    <a href="#plans">Plans</a>
                </li>
                                    <ul id="tocify-subheader-plans" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="plans-GETapi-v1-plans">
                                <a href="#plans-GETapi-v1-plans">List plans</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-subscription" class="tocify-header">
                <li class="tocify-item level-1" data-unique="subscription">
                    <a href="#subscription">Subscription</a>
                </li>
                                    <ul id="tocify-subheader-subscription" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="subscription-GETapi-v1-subscription">
                                <a href="#subscription-GETapi-v1-subscription">Show the subscription</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="subscription-POSTapi-v1-subscription-change-plan">
                                <a href="#subscription-POSTapi-v1-subscription-change-plan">Change plan</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="subscription-POSTapi-v1-subscription-renew">
                                <a href="#subscription-POSTapi-v1-subscription-renew">Renew (simulated payment)</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="subscription-POSTapi-v1-subscription-cancel">
                                <a href="#subscription-POSTapi-v1-subscription-cancel">Cancel</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-dashboard" class="tocify-header">
                <li class="tocify-item level-1" data-unique="dashboard">
                    <a href="#dashboard">Dashboard</a>
                </li>
                                    <ul id="tocify-subheader-dashboard" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="dashboard-GETapi-v1-dashboard">
                                <a href="#dashboard-GETapi-v1-dashboard">Company dashboard</a>
                            </li>
                                                                        </ul>
                            </ul>
                    <ul id="tocify-header-admin" class="tocify-header">
                <li class="tocify-item level-1" data-unique="admin">
                    <a href="#admin">Admin</a>
                </li>
                                    <ul id="tocify-subheader-admin" class="tocify-subheader">
                                                    <li class="tocify-item level-2" data-unique="admin-GETapi-v1-admin-plans">
                                <a href="#admin-GETapi-v1-admin-plans">List all plans</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-POSTapi-v1-admin-plans">
                                <a href="#admin-POSTapi-v1-admin-plans">Create a plan</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-PATCHapi-v1-admin-plans--plan_ulid-">
                                <a href="#admin-PATCHapi-v1-admin-plans--plan_ulid-">Update a plan</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-GETapi-v1-admin-tenants">
                                <a href="#admin-GETapi-v1-admin-tenants">List companies</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-GETapi-v1-admin-tenants--tenant_ulid-">
                                <a href="#admin-GETapi-v1-admin-tenants--tenant_ulid-">Show a company</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-POSTapi-v1-admin-tenants--tenant_ulid--suspend">
                                <a href="#admin-POSTapi-v1-admin-tenants--tenant_ulid--suspend">Suspend a company</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-POSTapi-v1-admin-tenants--tenant_ulid--reactivate">
                                <a href="#admin-POSTapi-v1-admin-tenants--tenant_ulid--reactivate">Reactivate a company</a>
                            </li>
                                                                                <li class="tocify-item level-2" data-unique="admin-GETapi-v1-admin-dashboard">
                                <a href="#admin-GETapi-v1-admin-dashboard">Platform dashboard</a>
                            </li>
                                                                        </ul>
                            </ul>
            </div>

    <ul class="toc-footer" id="toc-footer">
                    <li style="padding-bottom: 5px;"><a href="{{ route("scribe.postman") }}">View Postman collection</a></li>
                            <li style="padding-bottom: 5px;"><a href="{{ route("scribe.openapi") }}">View OpenAPI spec</a></li>
                <li><a href="http://github.com/knuckleswtf/scribe">Documentation powered by Scribe ✍</a></li>
    </ul>

    <ul class="toc-footer" id="last-updated">
        <li>Last updated: October 2, 2026</li>
    </ul>
</div>

<div class="page-wrapper">
    <div class="dark-box"></div>
    <div class="content">
        <h1 id="introduction">Introduction</h1>
<p>Multi-tenant SaaS backend: companies, their users and customers, subscription plans with feature limits, and usage analytics.</p>
<aside>
    <strong>Base URL</strong>: <code>http://localhost:8000</code>
</aside>
<pre><code>Every request sends `Accept: application/json`. Protected endpoints take a Sanctum token: `Authorization: Bearer &lt;token&gt;`.
Get a token from `POST /api/v1/auth/register` or `POST /api/v1/auth/login`. Tokens expire after 7 days.

Every error has the same shape:

```json
{"error": {"code": "PLAN_LIMIT_EXCEEDED", "message": "Your plan allows 5 users. Upgrade your plan to add more.", "details": {"feature": "max_users", "limit": 5, "current": 5}}}
```

IDs in URLs and responses are ULIDs. Lists take `page`, `per_page` (1 to 100, default 15), `sort` (a `-` prefix means descending), and `filter[...]`.
Unknown sort fields or filter keys return `422 VALIDATION_FAILED`.</code></pre>

        <h1 id="authenticating-requests">Authenticating requests</h1>
<p>To authenticate requests, include an <strong><code>Authorization</code></strong> header with the value <strong><code>"Bearer {YOUR_TOKEN}"</code></strong>.</p>
<p>All authenticated endpoints are marked with a <code>requires authentication</code> badge in the documentation below.</p>
<p>Get a token from <code>POST /api/v1/auth/register</code> or <code>POST /api/v1/auth/login</code>.</p>

        <h1 id="auth">Auth</h1>

    <p>Registration, login, and passwords.</p>

                                <h2 id="auth-POSTapi-v1-auth-register">Register a company</h2>

<p>
</p>

<p>Creates the company, its owner, and a subscription in one transaction, then returns a token so the client is logged in at once. A paid plan starts a 14-day trial; free starts active with no period. The owner gets a welcome email.</p>

<span id="example-requests-POSTapi-v1-auth-register">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/auth/register" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"company_name\": \"Rahim Traders\",
    \"name\": \"Rahim Uddin\",
    \"email\": \"rahim@rahimtraders.test\",
    \"password\": \"Secret123\",
    \"plan_code\": \"starter\"
}"
</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-auth-register">
            <blockquote>
            <p>Example response (201):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;token&quot;: &quot;4|Hu1GPwTOXbUkAbW5km3uXtV8KkDXLPh9GQrruBJUb6ed0a9e&quot;,
        &quot;user&quot;: {
            &quot;id&quot;: &quot;01m3yq2m67tyfq22zt14a0ymyq&quot;,
            &quot;name&quot;: &quot;Rahim Uddin&quot;,
            &quot;email&quot;: &quot;rahim@rahimtraders.test&quot;,
            &quot;role&quot;: &quot;owner&quot;,
            &quot;is_active&quot;: true,
            &quot;last_login_at&quot;: null,
            &quot;created_at&quot;: &quot;2026-10-02T16:27:37Z&quot;,
            &quot;tenant&quot;: {
                &quot;id&quot;: &quot;01m3yq2kzkqwbq7wbddstwev0v&quot;,
                &quot;name&quot;: &quot;Rahim Traders&quot;,
                &quot;status&quot;: &quot;active&quot;,
                &quot;created_at&quot;: &quot;2026-10-02T16:27:36Z&quot;
            }
        },
        &quot;tenant&quot;: {
            &quot;id&quot;: &quot;01m3yq2kzkqwbq7wbddstwev0v&quot;,
            &quot;name&quot;: &quot;Rahim Traders&quot;,
            &quot;status&quot;: &quot;active&quot;,
            &quot;created_at&quot;: &quot;2026-10-02T16:27:36Z&quot;
        },
        &quot;subscription&quot;: {
            &quot;id&quot;: &quot;01m3yq2m6ch3k7tjxzbr2528rx&quot;,
            &quot;status&quot;: &quot;trialing&quot;,
            &quot;plan&quot;: {
                &quot;id&quot;: &quot;01m3ypwrg7s3rw8thb4tw1226n&quot;,
                &quot;code&quot;: &quot;starter&quot;,
                &quot;name&quot;: &quot;Starter&quot;,
                &quot;price_cents&quot;: 1900,
                &quot;currency&quot;: &quot;USD&quot;,
                &quot;features&quot;: [
                    {
                        &quot;key&quot;: &quot;max_users&quot;,
                        &quot;enabled&quot;: true,
                        &quot;limit&quot;: 5
                    },
                    {
                        &quot;key&quot;: &quot;max_customers&quot;,
                        &quot;enabled&quot;: true,
                        &quot;limit&quot;: 500
                    },
                    {
                        &quot;key&quot;: &quot;api_rate_per_minute&quot;,
                        &quot;enabled&quot;: true,
                        &quot;limit&quot;: 120
                    },
                    {
                        &quot;key&quot;: &quot;customer_export&quot;,
                        &quot;enabled&quot;: true,
                        &quot;limit&quot;: null
                    },
                    {
                        &quot;key&quot;: &quot;analytics_trends&quot;,
                        &quot;enabled&quot;: false,
                        &quot;limit&quot;: null
                    }
                ]
            },
            &quot;trial_ends_at&quot;: &quot;2026-10-16T16:27:37Z&quot;,
            &quot;current_period_start&quot;: null,
            &quot;current_period_end&quot;: null,
            &quot;cancelled_at&quot;: null,
            &quot;ends_at&quot;: null,
            &quot;is_usable&quot;: true
        }
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;VALIDATION_FAILED&quot;,
        &quot;message&quot;: &quot;The given data was invalid.&quot;,
        &quot;details&quot;: {
            &quot;email&quot;: [
                &quot;The email has already been taken.&quot;
            ],
            &quot;plan_code&quot;: [
                &quot;The selected plan code is invalid.&quot;
            ]
        }
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (429, Rate limit hit (Retry-After header is set)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;RATE_LIMITED&quot;,
        &quot;message&quot;: &quot;Too many requests. Please slow down.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-auth-register" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-auth-register"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-register"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-auth-register" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-auth-register">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-auth-register" data-method="POST"
      data-path="api/v1/auth/register"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-register', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-auth-register"
                    onclick="tryItOut('POSTapi-v1-auth-register');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-auth-register"
                    onclick="cancelTryOut('POSTapi-v1-auth-register');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-auth-register"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/auth/register</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-auth-register"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-auth-register"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>company_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="company_name"                data-endpoint="POSTapi-v1-auth-register"
               value="Rahim Traders"
               data-component="body">
    <br>
<p>The company (tenant) name. Must not be greater than 255 characters. Example: <code>Rahim Traders</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-v1-auth-register"
               value="Rahim Uddin"
               data-component="body">
    <br>
<p>The owner full name. Must not be greater than 255 characters. Example: <code>Rahim Uddin</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-v1-auth-register"
               value="rahim@rahimtraders.test"
               data-component="body">
    <br>
<p>Owner email. Unique across the platform among non-deleted users. Must be a valid email address. Must not be greater than 255 characters. Example: <code>rahim@rahimtraders.test</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-v1-auth-register"
               value="Secret123"
               data-component="body">
    <br>
<p>At least 8 characters. Example: <code>Secret123</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>plan_code</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="plan_code"                data-endpoint="POSTapi-v1-auth-register"
               value="starter"
               data-component="body">
    <br>
<p>An active plan code: free, starter, or pro. Defaults to starter. A paid plan starts a 14-day trial. Must match an existing stored value. Example: <code>starter</code></p>
        </div>
        </form>

                    <h2 id="auth-POSTapi-v1-auth-login">Log in</h2>

<p>
</p>

<p>Returns a token named "api" that expires after 7 days. Unknown email, wrong password, and inactive user all return the same 401.</p>

<span id="example-requests-POSTapi-v1-auth-login">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/auth/login" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"email\": \"owner@acme.test\",
    \"password\": \"password\"
}"
</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-auth-login">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;token&quot;: &quot;5|SfKJB3rWYYrWWSjtZ8qr7QPPdNMRGuW9A96pMiRj2eb2a6f0&quot;,
        &quot;user&quot;: {
            &quot;id&quot;: &quot;01m3ypws7j18e4984mfb5kkz73&quot;,
            &quot;name&quot;: &quot;Acme Ltd Owner&quot;,
            &quot;email&quot;: &quot;owner@acme.test&quot;,
            &quot;role&quot;: &quot;owner&quot;,
            &quot;is_active&quot;: true,
            &quot;last_login_at&quot;: &quot;2026-10-02T16:27:37Z&quot;,
            &quot;created_at&quot;: &quot;2026-10-02T16:24:25Z&quot;,
            &quot;tenant&quot;: {
                &quot;id&quot;: &quot;01m3ypws0r7ercmyaecr9y5a12&quot;,
                &quot;name&quot;: &quot;Acme Ltd&quot;,
                &quot;status&quot;: &quot;active&quot;,
                &quot;created_at&quot;: &quot;2026-10-02T16:24:25Z&quot;
            }
        }
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Wrong email or password, or inactive user):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;INVALID_CREDENTIALS&quot;,
        &quot;message&quot;: &quot;These credentials do not match our records.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Company suspended):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;TENANT_SUSPENDED&quot;,
        &quot;message&quot;: &quot;Your company account is suspended.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;VALIDATION_FAILED&quot;,
        &quot;message&quot;: &quot;The given data was invalid.&quot;,
        &quot;details&quot;: {
            &quot;email&quot;: [
                &quot;The email field is required.&quot;
            ]
        }
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (429, Rate limit hit (Retry-After header is set)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;RATE_LIMITED&quot;,
        &quot;message&quot;: &quot;Too many requests. Please slow down.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-auth-login" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-auth-login"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-login"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-auth-login" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-auth-login">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-auth-login" data-method="POST"
      data-path="api/v1/auth/login"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-login', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-auth-login"
                    onclick="tryItOut('POSTapi-v1-auth-login');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-auth-login"
                    onclick="cancelTryOut('POSTapi-v1-auth-login');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-auth-login"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/auth/login</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-auth-login"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-auth-login"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-v1-auth-login"
               value="owner@acme.test"
               data-component="body">
    <br>
<p>Account email. Must be a valid email address. Example: <code>owner@acme.test</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-v1-auth-login"
               value="password"
               data-component="body">
    <br>
<p>Account password. Example: <code>password</code></p>
        </div>
        </form>

                    <h2 id="auth-POSTapi-v1-auth-forgot-password">Send a set-password token</h2>

<p>
</p>

<p>Emails a token for POST /auth/reset-password. Always returns 202, so the API never reveals which emails exist.</p>

<span id="example-requests-POSTapi-v1-auth-forgot-password">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/auth/forgot-password" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"email\": \"owner@acme.test\"
}"
</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-auth-forgot-password">
            <blockquote>
            <p>Example response (202):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;message&quot;: &quot;If the email is registered, a reset token has been sent.&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;VALIDATION_FAILED&quot;,
        &quot;message&quot;: &quot;The given data was invalid.&quot;,
        &quot;details&quot;: {
            &quot;email&quot;: [
                &quot;The email field must be a valid email address.&quot;
            ]
        }
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (429, Rate limit hit (Retry-After header is set)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;RATE_LIMITED&quot;,
        &quot;message&quot;: &quot;Too many requests. Please slow down.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-auth-forgot-password" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-auth-forgot-password"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-forgot-password"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-auth-forgot-password" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-auth-forgot-password">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-auth-forgot-password" data-method="POST"
      data-path="api/v1/auth/forgot-password"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-forgot-password', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-auth-forgot-password"
                    onclick="tryItOut('POSTapi-v1-auth-forgot-password');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-auth-forgot-password"
                    onclick="cancelTryOut('POSTapi-v1-auth-forgot-password');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-auth-forgot-password"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/auth/forgot-password</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-auth-forgot-password"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-auth-forgot-password"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-v1-auth-forgot-password"
               value="owner@acme.test"
               data-component="body">
    <br>
<p>Account email. The response is 202 whether or not it exists. Must be a valid email address. Example: <code>owner@acme.test</code></p>
        </div>
        </form>

                    <h2 id="auth-POSTapi-v1-auth-reset-password">Set a new password</h2>

<p>
</p>

<p>Uses the token from the forgot-password email or from the new-user invitation email.</p>

<span id="example-requests-POSTapi-v1-auth-reset-password">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/auth/reset-password" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"email\": \"owner@acme.test\",
    \"token\": \"b6f1c0d2e3a4f5b6c7d8e9f0a1b2c3d4e5f6a7b8c9d0e1f2a3b4c5d6e7f8a9b0\",
    \"password\": \"NewSecret123\"
}"
</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-auth-reset-password">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;message&quot;: &quot;Your password has been set.&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;VALIDATION_FAILED&quot;,
        &quot;message&quot;: &quot;The given data was invalid.&quot;,
        &quot;details&quot;: {
            &quot;token&quot;: [
                &quot;This password reset token is invalid.&quot;
            ]
        }
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (429, Rate limit hit (Retry-After header is set)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;RATE_LIMITED&quot;,
        &quot;message&quot;: &quot;Too many requests. Please slow down.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-auth-reset-password" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-auth-reset-password"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-reset-password"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-auth-reset-password" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-auth-reset-password">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-auth-reset-password" data-method="POST"
      data-path="api/v1/auth/reset-password"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-reset-password', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-auth-reset-password"
                    onclick="tryItOut('POSTapi-v1-auth-reset-password');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-auth-reset-password"
                    onclick="cancelTryOut('POSTapi-v1-auth-reset-password');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-auth-reset-password"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/auth/reset-password</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-auth-reset-password"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-auth-reset-password"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-v1-auth-reset-password"
               value="owner@acme.test"
               data-component="body">
    <br>
<p>Account email. Must be a valid email address. Example: <code>owner@acme.test</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>token</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="token"                data-endpoint="POSTapi-v1-auth-reset-password"
               value="b6f1c0d2e3a4f5b6c7d8e9f0a1b2c3d4e5f6a7b8c9d0e1f2a3b4c5d6e7f8a9b0"
               data-component="body">
    <br>
<p>The token from the set-password email. Example: <code>b6f1c0d2e3a4f5b6c7d8e9f0a1b2c3d4e5f6a7b8c9d0e1f2a3b4c5d6e7f8a9b0</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>password</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="password"                data-endpoint="POSTapi-v1-auth-reset-password"
               value="NewSecret123"
               data-component="body">
    <br>
<p>The new password. At least 8 characters. Example: <code>NewSecret123</code></p>
        </div>
        </form>

                    <h2 id="auth-POSTapi-v1-auth-logout">Log out</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Deletes only the token used for this request. Other devices stay logged in.</p>

<span id="example-requests-POSTapi-v1-auth-logout">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/auth/logout" \
    --header "Authorization: Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-auth-logout">
            <blockquote>
            <p>Example response (204, Logged out):</p>
        </blockquote>
                <pre>
<code>Empty response</code>
 </pre>
            <blockquote>
            <p>Example response (401, Missing, invalid, or expired token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;UNAUTHENTICATED&quot;,
        &quot;message&quot;: &quot;Unauthenticated.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (429, Rate limit hit (Retry-After header is set)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;RATE_LIMITED&quot;,
        &quot;message&quot;: &quot;Too many requests. Please slow down.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-auth-logout" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-auth-logout"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-auth-logout"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-auth-logout" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-auth-logout">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-auth-logout" data-method="POST"
      data-path="api/v1/auth/logout"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-auth-logout', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-auth-logout"
                    onclick="tryItOut('POSTapi-v1-auth-logout');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-auth-logout"
                    onclick="cancelTryOut('POSTapi-v1-auth-logout');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-auth-logout"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/auth/logout</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-auth-logout"
               value="Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-auth-logout"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-auth-logout"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="auth-GETapi-v1-auth-me">Current user</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Works for tenant users and platform admins. Returns the role, a company summary, and the permission list. A platform admin has no role, company, or permissions.</p>

<span id="example-requests-GETapi-v1-auth-me">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/auth/me" \
    --header "Authorization: Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-auth-me">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: &quot;01m3ypws7j18e4984mfb5kkz73&quot;,
        &quot;name&quot;: &quot;Acme Ltd Owner&quot;,
        &quot;email&quot;: &quot;owner@acme.test&quot;,
        &quot;role&quot;: &quot;owner&quot;,
        &quot;is_active&quot;: true,
        &quot;last_login_at&quot;: &quot;2026-10-02T16:27:37Z&quot;,
        &quot;created_at&quot;: &quot;2026-10-02T16:24:25Z&quot;,
        &quot;tenant&quot;: {
            &quot;id&quot;: &quot;01m3ypws0r7ercmyaecr9y5a12&quot;,
            &quot;name&quot;: &quot;Acme Ltd&quot;,
            &quot;status&quot;: &quot;active&quot;,
            &quot;created_at&quot;: &quot;2026-10-02T16:24:25Z&quot;
        },
        &quot;permissions&quot;: [
            &quot;tenant.view&quot;,
            &quot;tenant.update&quot;,
            &quot;users.view&quot;,
            &quot;users.create&quot;,
            &quot;users.update&quot;,
            &quot;users.delete&quot;,
            &quot;customers.view&quot;,
            &quot;customers.create&quot;,
            &quot;customers.update&quot;,
            &quot;customers.delete&quot;,
            &quot;customers.export&quot;,
            &quot;subscription.view&quot;,
            &quot;subscription.manage&quot;,
            &quot;dashboard.view&quot;
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Missing, invalid, or expired token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;UNAUTHENTICATED&quot;,
        &quot;message&quot;: &quot;Unauthenticated.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (429, Rate limit hit (Retry-After header is set)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;RATE_LIMITED&quot;,
        &quot;message&quot;: &quot;Too many requests. Please slow down.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-auth-me" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-auth-me"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-auth-me"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-auth-me" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-auth-me">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-auth-me" data-method="GET"
      data-path="api/v1/auth/me"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-auth-me', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-auth-me"
                    onclick="tryItOut('GETapi-v1-auth-me');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-auth-me"
                    onclick="cancelTryOut('GETapi-v1-auth-me');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-auth-me"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/auth/me</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-auth-me"
               value="Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-auth-me"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-auth-me"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                <h1 id="tenant">Tenant</h1>

    <p>Your company.</p>

                                <h2 id="tenant-GETapi-v1-tenant">Show your company</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Needs the tenant.view permission.</p>

<span id="example-requests-GETapi-v1-tenant">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/tenant" \
    --header "Authorization: Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-tenant">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: &quot;01m3ypws0r7ercmyaecr9y5a12&quot;,
        &quot;name&quot;: &quot;Acme Ltd&quot;,
        &quot;status&quot;: &quot;active&quot;,
        &quot;created_at&quot;: &quot;2026-10-02T16:24:25Z&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Missing, invalid, or expired token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;UNAUTHENTICATED&quot;,
        &quot;message&quot;: &quot;Unauthenticated.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, No permission, or wrong user type for this route):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;FORBIDDEN&quot;,
        &quot;message&quot;: &quot;This action is unauthorized.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Company suspended):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;TENANT_SUSPENDED&quot;,
        &quot;message&quot;: &quot;Your company account is suspended.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (429, Rate limit hit (Retry-After header is set)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;RATE_LIMITED&quot;,
        &quot;message&quot;: &quot;Too many requests. Please slow down.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-tenant" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-tenant"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-tenant"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-tenant" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-tenant">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-tenant" data-method="GET"
      data-path="api/v1/tenant"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-tenant', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-tenant"
                    onclick="tryItOut('GETapi-v1-tenant');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-tenant"
                    onclick="cancelTryOut('GETapi-v1-tenant');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-tenant"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/tenant</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-tenant"
               value="Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-tenant"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-tenant"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="tenant-PATCHapi-v1-tenant">Rename your company</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Needs the tenant.update permission (owner only).</p>

<span id="example-requests-PATCHapi-v1-tenant">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PATCH \
    "http://localhost:8000/api/v1/tenant" \
    --header "Authorization: Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Acme Limited\"
}"
</code></pre></div>

</span>

<span id="example-responses-PATCHapi-v1-tenant">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: &quot;01m3ypws0r7ercmyaecr9y5a12&quot;,
        &quot;name&quot;: &quot;Acme Limited&quot;,
        &quot;status&quot;: &quot;active&quot;,
        &quot;created_at&quot;: &quot;2026-10-02T16:24:25Z&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Missing, invalid, or expired token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;UNAUTHENTICATED&quot;,
        &quot;message&quot;: &quot;Unauthenticated.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, No permission, or wrong user type for this route):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;FORBIDDEN&quot;,
        &quot;message&quot;: &quot;This action is unauthorized.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Company suspended):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;TENANT_SUSPENDED&quot;,
        &quot;message&quot;: &quot;Your company account is suspended.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;VALIDATION_FAILED&quot;,
        &quot;message&quot;: &quot;The given data was invalid.&quot;,
        &quot;details&quot;: {
            &quot;name&quot;: [
                &quot;The name field is required.&quot;
            ]
        }
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (429, Rate limit hit (Retry-After header is set)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;RATE_LIMITED&quot;,
        &quot;message&quot;: &quot;Too many requests. Please slow down.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
    </span>
<span id="execution-results-PATCHapi-v1-tenant" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PATCHapi-v1-tenant"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PATCHapi-v1-tenant"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PATCHapi-v1-tenant" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PATCHapi-v1-tenant">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PATCHapi-v1-tenant" data-method="PATCH"
      data-path="api/v1/tenant"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PATCHapi-v1-tenant', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PATCHapi-v1-tenant"
                    onclick="tryItOut('PATCHapi-v1-tenant');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PATCHapi-v1-tenant"
                    onclick="cancelTryOut('PATCHapi-v1-tenant');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PATCHapi-v1-tenant"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/v1/tenant</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PATCHapi-v1-tenant"
               value="Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PATCHapi-v1-tenant"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PATCHapi-v1-tenant"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PATCHapi-v1-tenant"
               value="Acme Limited"
               data-component="body">
    <br>
<p>The new company name. Must not be greater than 255 characters. Example: <code>Acme Limited</code></p>
        </div>
        </form>

                <h1 id="users">Users</h1>

    <p>Staff users of your company.</p>

                                <h2 id="users-GETapi-v1-users">List users</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Your company users only. Needs users.view.</p>

<span id="example-requests-GETapi-v1-users">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/users?page=1&amp;per_page=15&amp;sort=name&amp;filter[role]=member&amp;filter[is_active]=1&amp;filter[search]=kar" \
    --header "Authorization: Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-users">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: &quot;01m3ypwsxybe30vqk6h7k91gbr&quot;,
            &quot;name&quot;: &quot;Acme Member&quot;,
            &quot;email&quot;: &quot;member@acme.test&quot;,
            &quot;role&quot;: &quot;member&quot;,
            &quot;is_active&quot;: true,
            &quot;last_login_at&quot;: null,
            &quot;created_at&quot;: &quot;2026-10-02T16:24:26Z&quot;
        }
    ],
    &quot;links&quot;: {
        &quot;first&quot;: &quot;http://localhost:8000/api/v1/users?page=1&quot;,
        &quot;last&quot;: &quot;http://localhost:8000/api/v1/users?page=14&quot;,
        &quot;prev&quot;: null,
        &quot;next&quot;: &quot;http://localhost:8000/api/v1/users?page=2&quot;
    },
    &quot;meta&quot;: {
        &quot;current_page&quot;: 1,
        &quot;from&quot;: 1,
        &quot;last_page&quot;: 14,
        &quot;path&quot;: &quot;http://localhost:8000/api/v1/users&quot;,
        &quot;per_page&quot;: 15,
        &quot;to&quot;: 15,
        &quot;total&quot;: 200
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Missing, invalid, or expired token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;UNAUTHENTICATED&quot;,
        &quot;message&quot;: &quot;Unauthenticated.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, No permission, or wrong user type for this route):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;FORBIDDEN&quot;,
        &quot;message&quot;: &quot;This action is unauthorized.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Company suspended):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;TENANT_SUSPENDED&quot;,
        &quot;message&quot;: &quot;Your company account is suspended.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;VALIDATION_FAILED&quot;,
        &quot;message&quot;: &quot;The given data was invalid.&quot;,
        &quot;details&quot;: {
            &quot;sort&quot;: [
                &quot;The selected sort is invalid.&quot;
            ]
        }
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (429, Rate limit hit (Retry-After header is set)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;RATE_LIMITED&quot;,
        &quot;message&quot;: &quot;Too many requests. Please slow down.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-users" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-users"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-users"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-users" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-users">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-users" data-method="GET"
      data-path="api/v1/users"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-users', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-users"
                    onclick="tryItOut('GETapi-v1-users');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-users"
                    onclick="cancelTryOut('GETapi-v1-users');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-users"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/users</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-users"
               value="Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-users"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-users"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="page"                data-endpoint="GETapi-v1-users"
               value="1"
               data-component="query">
    <br>
<p>Page number, from 1. Must be at least 1. Must not be greater than 1000000. Example: <code>1</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-v1-users"
               value="15"
               data-component="query">
    <br>
<p>Items per page, 1 to 100. Default 15. Must be at least 1. Must not be greater than 100. Example: <code>15</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>sort</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="sort"                data-endpoint="GETapi-v1-users"
               value="name"
               data-component="query">
    <br>
<p>-created_at (default) or name. Example: <code>name</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>-created_at</code></li> <li><code>name</code></li></ul>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>filter</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="filter"                data-endpoint="GETapi-v1-users"
               value=""
               data-component="query">
    <br>

            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>filter.role</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="filter.role"                data-endpoint="GETapi-v1-users"
               value="member"
               data-component="query">
    <br>
<p>owner, admin, or member. Example: <code>member</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>owner</code></li> <li><code>admin</code></li> <li><code>member</code></li></ul>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>filter.is_active</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="filter.is_active"                data-endpoint="GETapi-v1-users"
               value=""
               data-component="query">
    <br>
<p>1, 0, true, or false. Example: <code>true</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>1</code></li> <li><code>0</code></li> <li><code>true</code></li> <li><code>false</code></li></ul>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>filter.search</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="filter.search"                data-endpoint="GETapi-v1-users"
               value="kar"
               data-component="query">
    <br>
<p>Prefix match on name or email. Must not be greater than 100 characters. Example: <code>kar</code></p>
            </div>
                </form>

                    <h2 id="users-POSTapi-v1-users">Create a user</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>The user gets a random password and an email with a token to set their own. Counts against the plan max_users. Only an owner can assign the owner role.</p>

<span id="example-requests-POSTapi-v1-users">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/users" \
    --header "Authorization: Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Karim Hossain\",
    \"email\": \"karim@acme.test\",
    \"role\": \"member\"
}"
</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-users">
            <blockquote>
            <p>Example response (201):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: &quot;01m3ypwsxybe30vqk6h7k91gbr&quot;,
        &quot;name&quot;: &quot;Acme Member&quot;,
        &quot;email&quot;: &quot;member@acme.test&quot;,
        &quot;role&quot;: &quot;member&quot;,
        &quot;is_active&quot;: true,
        &quot;last_login_at&quot;: null,
        &quot;created_at&quot;: &quot;2026-10-02T16:24:26Z&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Missing, invalid, or expired token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;UNAUTHENTICATED&quot;,
        &quot;message&quot;: &quot;Unauthenticated.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (402, Subscription not usable (writes only)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;SUBSCRIPTION_INACTIVE&quot;,
        &quot;message&quot;: &quot;Your subscription is not active. Renew it to make changes.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, No permission, or wrong user type for this route):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;FORBIDDEN&quot;,
        &quot;message&quot;: &quot;This action is unauthorized.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Company suspended):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;TENANT_SUSPENDED&quot;,
        &quot;message&quot;: &quot;Your company account is suspended.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Plan user limit reached):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;PLAN_LIMIT_EXCEEDED&quot;,
        &quot;message&quot;: &quot;Your plan allows 5 users. Upgrade your plan to add more.&quot;,
        &quot;details&quot;: {
            &quot;feature&quot;: &quot;max_users&quot;,
            &quot;limit&quot;: 5,
            &quot;current&quot;: 5
        }
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;VALIDATION_FAILED&quot;,
        &quot;message&quot;: &quot;The given data was invalid.&quot;,
        &quot;details&quot;: {
            &quot;email&quot;: [
                &quot;The email has already been taken.&quot;
            ]
        }
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (429, Rate limit hit (Retry-After header is set)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;RATE_LIMITED&quot;,
        &quot;message&quot;: &quot;Too many requests. Please slow down.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-users" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-users"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-users"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-users" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-users">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-users" data-method="POST"
      data-path="api/v1/users"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-users', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-users"
                    onclick="tryItOut('POSTapi-v1-users');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-users"
                    onclick="cancelTryOut('POSTapi-v1-users');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-users"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/users</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-users"
               value="Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-users"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-users"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-v1-users"
               value="Karim Hossain"
               data-component="body">
    <br>
<p>Full name. Must not be greater than 255 characters. Example: <code>Karim Hossain</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-v1-users"
               value="karim@acme.test"
               data-component="body">
    <br>
<p>Unique across the platform among non-deleted users. A set-password email is sent here. Must be a valid email address. Must not be greater than 255 characters. Example: <code>karim@acme.test</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>role</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="role"                data-endpoint="POSTapi-v1-users"
               value="member"
               data-component="body">
    <br>
<p>owner, admin, or member. Only an owner can assign owner. Example: <code>member</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>owner</code></li> <li><code>admin</code></li> <li><code>member</code></li></ul>
        </div>
        </form>

                    <h2 id="users-GETapi-v1-users--user_ulid-">Show a user</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>A user from another company returns 404.</p>

<span id="example-requests-GETapi-v1-users--user_ulid-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/users/01m3yqq286vdt8z2xza0zrwkm5" \
    --header "Authorization: Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-users--user_ulid-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: &quot;01m3ypwsxybe30vqk6h7k91gbr&quot;,
        &quot;name&quot;: &quot;Acme Member&quot;,
        &quot;email&quot;: &quot;member@acme.test&quot;,
        &quot;role&quot;: &quot;member&quot;,
        &quot;is_active&quot;: true,
        &quot;last_login_at&quot;: null,
        &quot;created_at&quot;: &quot;2026-10-02T16:24:26Z&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Missing, invalid, or expired token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;UNAUTHENTICATED&quot;,
        &quot;message&quot;: &quot;Unauthenticated.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, No permission, or wrong user type for this route):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;FORBIDDEN&quot;,
        &quot;message&quot;: &quot;This action is unauthorized.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Company suspended):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;TENANT_SUSPENDED&quot;,
        &quot;message&quot;: &quot;Your company account is suspended.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not found or belongs to another company):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;NOT_FOUND&quot;,
        &quot;message&quot;: &quot;The requested resource was not found.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (429, Rate limit hit (Retry-After header is set)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;RATE_LIMITED&quot;,
        &quot;message&quot;: &quot;Too many requests. Please slow down.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-users--user_ulid-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-users--user_ulid-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-users--user_ulid-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-users--user_ulid-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-users--user_ulid-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-users--user_ulid-" data-method="GET"
      data-path="api/v1/users/{user_ulid}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-users--user_ulid-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-users--user_ulid-"
                    onclick="tryItOut('GETapi-v1-users--user_ulid-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-users--user_ulid-"
                    onclick="cancelTryOut('GETapi-v1-users--user_ulid-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-users--user_ulid-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/users/{user_ulid}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-users--user_ulid-"
               value="Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-users--user_ulid-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-users--user_ulid-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>user_ulid</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="user_ulid"                data-endpoint="GETapi-v1-users--user_ulid-"
               value="01m3yqq286vdt8z2xza0zrwkm5"
               data-component="url">
    <br>
<p>Example: <code>01m3yqq286vdt8z2xza0zrwkm5</code></p>
            </div>
                    </form>

                    <h2 id="users-PATCHapi-v1-users--user_ulid-">Update a user</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>An admin cannot change an owner. Deactivating revokes all the user tokens. The last active owner cannot be demoted or deactivated.</p>

<span id="example-requests-PATCHapi-v1-users--user_ulid-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PATCH \
    "http://localhost:8000/api/v1/users/01m3yqq286vdt8z2xza0zrwkm5" \
    --header "Authorization: Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Karim Hossain\",
    \"role\": \"admin\",
    \"is_active\": true
}"
</code></pre></div>

</span>

<span id="example-responses-PATCHapi-v1-users--user_ulid-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: &quot;01m3ypwsxybe30vqk6h7k91gbr&quot;,
        &quot;name&quot;: &quot;Acme Member&quot;,
        &quot;email&quot;: &quot;member@acme.test&quot;,
        &quot;role&quot;: &quot;admin&quot;,
        &quot;is_active&quot;: true,
        &quot;last_login_at&quot;: null,
        &quot;created_at&quot;: &quot;2026-10-02T16:24:26Z&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Missing, invalid, or expired token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;UNAUTHENTICATED&quot;,
        &quot;message&quot;: &quot;Unauthenticated.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (402, Subscription not usable (writes only)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;SUBSCRIPTION_INACTIVE&quot;,
        &quot;message&quot;: &quot;Your subscription is not active. Renew it to make changes.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, No permission, or wrong user type for this route):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;FORBIDDEN&quot;,
        &quot;message&quot;: &quot;This action is unauthorized.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Company suspended):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;TENANT_SUSPENDED&quot;,
        &quot;message&quot;: &quot;Your company account is suspended.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not found or belongs to another company):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;NOT_FOUND&quot;,
        &quot;message&quot;: &quot;The requested resource was not found.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (409, Would leave the company without an owner):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;LAST_OWNER&quot;,
        &quot;message&quot;: &quot;A company must keep at least one active owner.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;VALIDATION_FAILED&quot;,
        &quot;message&quot;: &quot;The given data was invalid.&quot;,
        &quot;details&quot;: {
            &quot;role&quot;: [
                &quot;The selected role is invalid.&quot;
            ]
        }
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (429, Rate limit hit (Retry-After header is set)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;RATE_LIMITED&quot;,
        &quot;message&quot;: &quot;Too many requests. Please slow down.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
    </span>
<span id="execution-results-PATCHapi-v1-users--user_ulid-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PATCHapi-v1-users--user_ulid-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PATCHapi-v1-users--user_ulid-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PATCHapi-v1-users--user_ulid-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PATCHapi-v1-users--user_ulid-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PATCHapi-v1-users--user_ulid-" data-method="PATCH"
      data-path="api/v1/users/{user_ulid}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PATCHapi-v1-users--user_ulid-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PATCHapi-v1-users--user_ulid-"
                    onclick="tryItOut('PATCHapi-v1-users--user_ulid-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PATCHapi-v1-users--user_ulid-"
                    onclick="cancelTryOut('PATCHapi-v1-users--user_ulid-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PATCHapi-v1-users--user_ulid-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/v1/users/{user_ulid}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PATCHapi-v1-users--user_ulid-"
               value="Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PATCHapi-v1-users--user_ulid-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PATCHapi-v1-users--user_ulid-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>user_ulid</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="user_ulid"                data-endpoint="PATCHapi-v1-users--user_ulid-"
               value="01m3yqq286vdt8z2xza0zrwkm5"
               data-component="url">
    <br>
<p>Example: <code>01m3yqq286vdt8z2xza0zrwkm5</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PATCHapi-v1-users--user_ulid-"
               value="Karim Hossain"
               data-component="body">
    <br>
<p>Full name. Must not be greater than 255 characters. Example: <code>Karim Hossain</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>role</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="role"                data-endpoint="PATCHapi-v1-users--user_ulid-"
               value="admin"
               data-component="body">
    <br>
<p>owner, admin, or member. Only an owner can assign owner. Example: <code>admin</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>owner</code></li> <li><code>admin</code></li> <li><code>member</code></li></ul>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_active</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="PATCHapi-v1-users--user_ulid-" style="display: none">
            <input type="radio" name="is_active"
                   value="true"
                   data-endpoint="PATCHapi-v1-users--user_ulid-"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="PATCHapi-v1-users--user_ulid-" style="display: none">
            <input type="radio" name="is_active"
                   value="false"
                   data-endpoint="PATCHapi-v1-users--user_ulid-"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>false deactivates the user and revokes all their tokens. Example: <code>true</code></p>
        </div>
        </form>

                    <h2 id="users-DELETEapi-v1-users--user_ulid-">Delete a user</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Soft delete. Revokes the user tokens. Nobody can delete themselves, and an admin cannot delete an owner.</p>

<span id="example-requests-DELETEapi-v1-users--user_ulid-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost:8000/api/v1/users/01m3yqq286vdt8z2xza0zrwkm5" \
    --header "Authorization: Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>

</span>

<span id="example-responses-DELETEapi-v1-users--user_ulid-">
            <blockquote>
            <p>Example response (204, Deleted):</p>
        </blockquote>
                <pre>
<code>Empty response</code>
 </pre>
            <blockquote>
            <p>Example response (401, Missing, invalid, or expired token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;UNAUTHENTICATED&quot;,
        &quot;message&quot;: &quot;Unauthenticated.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (402, Subscription not usable (writes only)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;SUBSCRIPTION_INACTIVE&quot;,
        &quot;message&quot;: &quot;Your subscription is not active. Renew it to make changes.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, No permission, or wrong user type for this route):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;FORBIDDEN&quot;,
        &quot;message&quot;: &quot;This action is unauthorized.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Company suspended):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;TENANT_SUSPENDED&quot;,
        &quot;message&quot;: &quot;Your company account is suspended.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not found or belongs to another company):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;NOT_FOUND&quot;,
        &quot;message&quot;: &quot;The requested resource was not found.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (409, Would leave the company without an owner):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;LAST_OWNER&quot;,
        &quot;message&quot;: &quot;A company must keep at least one active owner.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (429, Rate limit hit (Retry-After header is set)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;RATE_LIMITED&quot;,
        &quot;message&quot;: &quot;Too many requests. Please slow down.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
    </span>
<span id="execution-results-DELETEapi-v1-users--user_ulid-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-v1-users--user_ulid-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-v1-users--user_ulid-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-v1-users--user_ulid-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-v1-users--user_ulid-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-v1-users--user_ulid-" data-method="DELETE"
      data-path="api/v1/users/{user_ulid}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-users--user_ulid-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-v1-users--user_ulid-"
                    onclick="tryItOut('DELETEapi-v1-users--user_ulid-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-v1-users--user_ulid-"
                    onclick="cancelTryOut('DELETEapi-v1-users--user_ulid-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-v1-users--user_ulid-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/v1/users/{user_ulid}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="DELETEapi-v1-users--user_ulid-"
               value="Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-v1-users--user_ulid-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-v1-users--user_ulid-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>user_ulid</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="user_ulid"                data-endpoint="DELETEapi-v1-users--user_ulid-"
               value="01m3yqq286vdt8z2xza0zrwkm5"
               data-component="url">
    <br>
<p>Example: <code>01m3yqq286vdt8z2xza0zrwkm5</code></p>
            </div>
                    </form>

                <h1 id="customers">Customers</h1>

    <p>Your company customers.</p>

                                <h2 id="customers-GETapi-v1-customers">List customers</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Your company customers only. Needs customers.view.</p>

<span id="example-requests-GETapi-v1-customers">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/customers?page=1&amp;per_page=15&amp;sort=-created_at&amp;filter[status]=active&amp;filter[search]=kar&amp;filter[created_from]=2026-09-01&amp;filter[created_to]=2026-09-30" \
    --header "Authorization: Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-customers">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: &quot;01m3yq2mys56kee6prc9xsn2ac&quot;,
            &quot;name&quot;: &quot;Karim Store&quot;,
            &quot;email&quot;: &quot;karim@store.test&quot;,
            &quot;phone&quot;: &quot;+8801700000000&quot;,
            &quot;company_name&quot;: &quot;Karim Ltd&quot;,
            &quot;status&quot;: &quot;active&quot;,
            &quot;created_by&quot;: {
                &quot;id&quot;: &quot;01m3ypws7j18e4984mfb5kkz73&quot;,
                &quot;name&quot;: &quot;Acme Ltd Owner&quot;
            },
            &quot;created_at&quot;: &quot;2026-10-02T16:27:37Z&quot;,
            &quot;updated_at&quot;: &quot;2026-10-02T16:27:37Z&quot;
        }
    ],
    &quot;links&quot;: {
        &quot;first&quot;: &quot;http://localhost:8000/api/v1/customers?page=1&quot;,
        &quot;last&quot;: &quot;http://localhost:8000/api/v1/customers?page=14&quot;,
        &quot;prev&quot;: null,
        &quot;next&quot;: &quot;http://localhost:8000/api/v1/customers?page=2&quot;
    },
    &quot;meta&quot;: {
        &quot;current_page&quot;: 1,
        &quot;from&quot;: 1,
        &quot;last_page&quot;: 14,
        &quot;path&quot;: &quot;http://localhost:8000/api/v1/customers&quot;,
        &quot;per_page&quot;: 15,
        &quot;to&quot;: 15,
        &quot;total&quot;: 200
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Missing, invalid, or expired token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;UNAUTHENTICATED&quot;,
        &quot;message&quot;: &quot;Unauthenticated.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, No permission, or wrong user type for this route):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;FORBIDDEN&quot;,
        &quot;message&quot;: &quot;This action is unauthorized.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Company suspended):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;TENANT_SUSPENDED&quot;,
        &quot;message&quot;: &quot;Your company account is suspended.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;VALIDATION_FAILED&quot;,
        &quot;message&quot;: &quot;The given data was invalid.&quot;,
        &quot;details&quot;: {
            &quot;filter&quot;: [
                &quot;The filter field must be an array with only the keys: status, search, created_from, created_to.&quot;
            ]
        }
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (429, Rate limit hit (Retry-After header is set)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;RATE_LIMITED&quot;,
        &quot;message&quot;: &quot;Too many requests. Please slow down.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-customers" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-customers"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-customers"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-customers" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-customers">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-customers" data-method="GET"
      data-path="api/v1/customers"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-customers', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-customers"
                    onclick="tryItOut('GETapi-v1-customers');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-customers"
                    onclick="cancelTryOut('GETapi-v1-customers');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-customers"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/customers</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-customers"
               value="Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-customers"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-customers"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="page"                data-endpoint="GETapi-v1-customers"
               value="1"
               data-component="query">
    <br>
<p>Page number, from 1. Must be at least 1. Must not be greater than 1000000. Example: <code>1</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-v1-customers"
               value="15"
               data-component="query">
    <br>
<p>Items per page, 1 to 100. Default 15. Must be at least 1. Must not be greater than 100. Example: <code>15</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>sort</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="sort"                data-endpoint="GETapi-v1-customers"
               value="-created_at"
               data-component="query">
    <br>
<p>-created_at (default), created_at, name, or -name. Example: <code>-created_at</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>-created_at</code></li> <li><code>created_at</code></li> <li><code>name</code></li> <li><code>-name</code></li></ul>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>filter</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="filter"                data-endpoint="GETapi-v1-customers"
               value=""
               data-component="query">
    <br>

            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>filter.status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="filter.status"                data-endpoint="GETapi-v1-customers"
               value="active"
               data-component="query">
    <br>
<p>active or inactive. Example: <code>active</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>active</code></li> <li><code>inactive</code></li></ul>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>filter.search</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="filter.search"                data-endpoint="GETapi-v1-customers"
               value="kar"
               data-component="query">
    <br>
<p>Prefix match on name or email. Must not be greater than 100 characters. Example: <code>kar</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>filter.created_from</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="filter.created_from"                data-endpoint="GETapi-v1-customers"
               value="2026-09-01"
               data-component="query">
    <br>
<p>Created on or after this date (Y-m-d). Must be a valid date in the format <code>Y-m-d</code>. Example: <code>2026-09-01</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>filter.created_to</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="filter.created_to"                data-endpoint="GETapi-v1-customers"
               value="2026-09-30"
               data-component="query">
    <br>
<p>Created on or before this date (Y-m-d). Must be a valid date in the format <code>Y-m-d</code>. Must be a date after or equal to <code>filter.created_from</code>. Example: <code>2026-09-30</code></p>
            </div>
                </form>

                    <h2 id="customers-POSTapi-v1-customers">Create a customer</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Counts against the plan max_customers. Any tenant_id in the body is ignored.</p>

<span id="example-requests-POSTapi-v1-customers">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/customers" \
    --header "Authorization: Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Karim Store\",
    \"email\": \"karim@store.test\",
    \"phone\": \"+8801700000000\",
    \"company_name\": \"Karim Ltd\",
    \"status\": \"active\"
}"
</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-customers">
            <blockquote>
            <p>Example response (201):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: &quot;01m3yq2mys56kee6prc9xsn2ac&quot;,
        &quot;name&quot;: &quot;Karim Store&quot;,
        &quot;email&quot;: &quot;karim@store.test&quot;,
        &quot;phone&quot;: &quot;+8801700000000&quot;,
        &quot;company_name&quot;: &quot;Karim Ltd&quot;,
        &quot;status&quot;: &quot;active&quot;,
        &quot;created_by&quot;: {
            &quot;id&quot;: &quot;01m3ypws7j18e4984mfb5kkz73&quot;,
            &quot;name&quot;: &quot;Acme Ltd Owner&quot;
        },
        &quot;created_at&quot;: &quot;2026-10-02T16:27:37Z&quot;,
        &quot;updated_at&quot;: &quot;2026-10-02T16:27:37Z&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Missing, invalid, or expired token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;UNAUTHENTICATED&quot;,
        &quot;message&quot;: &quot;Unauthenticated.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (402, Subscription not usable (writes only)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;SUBSCRIPTION_INACTIVE&quot;,
        &quot;message&quot;: &quot;Your subscription is not active. Renew it to make changes.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, No permission, or wrong user type for this route):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;FORBIDDEN&quot;,
        &quot;message&quot;: &quot;This action is unauthorized.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Company suspended):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;TENANT_SUSPENDED&quot;,
        &quot;message&quot;: &quot;Your company account is suspended.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Plan customer limit reached):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;PLAN_LIMIT_EXCEEDED&quot;,
        &quot;message&quot;: &quot;Your plan allows 500 customers. Upgrade your plan to add more.&quot;,
        &quot;details&quot;: {
            &quot;feature&quot;: &quot;max_customers&quot;,
            &quot;limit&quot;: 500,
            &quot;current&quot;: 500
        }
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;VALIDATION_FAILED&quot;,
        &quot;message&quot;: &quot;The given data was invalid.&quot;,
        &quot;details&quot;: {
            &quot;email&quot;: [
                &quot;The email has already been taken.&quot;
            ]
        }
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (429, Rate limit hit (Retry-After header is set)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;RATE_LIMITED&quot;,
        &quot;message&quot;: &quot;Too many requests. Please slow down.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-customers" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-customers"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-customers"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-customers" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-customers">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-customers" data-method="POST"
      data-path="api/v1/customers"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-customers', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-customers"
                    onclick="tryItOut('POSTapi-v1-customers');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-customers"
                    onclick="cancelTryOut('POSTapi-v1-customers');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-customers"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/customers</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-customers"
               value="Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-customers"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-customers"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-v1-customers"
               value="Karim Store"
               data-component="body">
    <br>
<p>Customer name. Must not be greater than 255 characters. Example: <code>Karim Store</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="POSTapi-v1-customers"
               value="karim@store.test"
               data-component="body">
    <br>
<p>Unique within your company among non-deleted customers. Must be a valid email address. Must not be greater than 255 characters. Example: <code>karim@store.test</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>phone</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="phone"                data-endpoint="POSTapi-v1-customers"
               value="+8801700000000"
               data-component="body">
    <br>
<p>Optional phone number. Must not be greater than 30 characters. Example: <code>+8801700000000</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>company_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="company_name"                data-endpoint="POSTapi-v1-customers"
               value="Karim Ltd"
               data-component="body">
    <br>
<p>Optional company name. Must not be greater than 255 characters. Example: <code>Karim Ltd</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="POSTapi-v1-customers"
               value="active"
               data-component="body">
    <br>
<p>active or inactive. Defaults to active. Example: <code>active</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>active</code></li> <li><code>inactive</code></li></ul>
        </div>
        </form>

                    <h2 id="customers-GETapi-v1-customers--customer_ulid-">Show a customer</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>A customer from another company returns 404.</p>

<span id="example-requests-GETapi-v1-customers--customer_ulid-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/customers/architecto" \
    --header "Authorization: Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-customers--customer_ulid-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: &quot;01m3yq2mys56kee6prc9xsn2ac&quot;,
        &quot;name&quot;: &quot;Karim Store&quot;,
        &quot;email&quot;: &quot;karim@store.test&quot;,
        &quot;phone&quot;: &quot;+8801700000000&quot;,
        &quot;company_name&quot;: &quot;Karim Ltd&quot;,
        &quot;status&quot;: &quot;active&quot;,
        &quot;created_by&quot;: {
            &quot;id&quot;: &quot;01m3ypws7j18e4984mfb5kkz73&quot;,
            &quot;name&quot;: &quot;Acme Ltd Owner&quot;
        },
        &quot;created_at&quot;: &quot;2026-10-02T16:27:37Z&quot;,
        &quot;updated_at&quot;: &quot;2026-10-02T16:27:37Z&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Missing, invalid, or expired token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;UNAUTHENTICATED&quot;,
        &quot;message&quot;: &quot;Unauthenticated.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, No permission, or wrong user type for this route):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;FORBIDDEN&quot;,
        &quot;message&quot;: &quot;This action is unauthorized.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Company suspended):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;TENANT_SUSPENDED&quot;,
        &quot;message&quot;: &quot;Your company account is suspended.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not found or belongs to another company):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;NOT_FOUND&quot;,
        &quot;message&quot;: &quot;The requested resource was not found.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (429, Rate limit hit (Retry-After header is set)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;RATE_LIMITED&quot;,
        &quot;message&quot;: &quot;Too many requests. Please slow down.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-customers--customer_ulid-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-customers--customer_ulid-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-customers--customer_ulid-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-customers--customer_ulid-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-customers--customer_ulid-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-customers--customer_ulid-" data-method="GET"
      data-path="api/v1/customers/{customer_ulid}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-customers--customer_ulid-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-customers--customer_ulid-"
                    onclick="tryItOut('GETapi-v1-customers--customer_ulid-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-customers--customer_ulid-"
                    onclick="cancelTryOut('GETapi-v1-customers--customer_ulid-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-customers--customer_ulid-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/customers/{customer_ulid}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-customers--customer_ulid-"
               value="Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-customers--customer_ulid-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-customers--customer_ulid-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>customer_ulid</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="customer_ulid"                data-endpoint="GETapi-v1-customers--customer_ulid-"
               value="architecto"
               data-component="url">
    <br>
<p>Example: <code>architecto</code></p>
            </div>
                    </form>

                    <h2 id="customers-PATCHapi-v1-customers--customer_ulid-">Update a customer</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Send only the fields to change.</p>

<span id="example-requests-PATCHapi-v1-customers--customer_ulid-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PATCH \
    "http://localhost:8000/api/v1/customers/architecto" \
    --header "Authorization: Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Karim Store\",
    \"email\": \"karim@store.test\",
    \"phone\": \"+8801700000000\",
    \"company_name\": \"Karim Ltd\",
    \"status\": \"inactive\"
}"
</code></pre></div>

</span>

<span id="example-responses-PATCHapi-v1-customers--customer_ulid-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: &quot;01m3yq2mys56kee6prc9xsn2ac&quot;,
        &quot;name&quot;: &quot;Karim Store&quot;,
        &quot;email&quot;: &quot;karim@store.test&quot;,
        &quot;phone&quot;: &quot;+8801700000000&quot;,
        &quot;company_name&quot;: &quot;Karim Ltd&quot;,
        &quot;status&quot;: &quot;inactive&quot;,
        &quot;created_by&quot;: {
            &quot;id&quot;: &quot;01m3ypws7j18e4984mfb5kkz73&quot;,
            &quot;name&quot;: &quot;Acme Ltd Owner&quot;
        },
        &quot;created_at&quot;: &quot;2026-10-02T16:27:37Z&quot;,
        &quot;updated_at&quot;: &quot;2026-10-02T16:27:37Z&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Missing, invalid, or expired token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;UNAUTHENTICATED&quot;,
        &quot;message&quot;: &quot;Unauthenticated.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (402, Subscription not usable (writes only)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;SUBSCRIPTION_INACTIVE&quot;,
        &quot;message&quot;: &quot;Your subscription is not active. Renew it to make changes.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, No permission, or wrong user type for this route):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;FORBIDDEN&quot;,
        &quot;message&quot;: &quot;This action is unauthorized.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Company suspended):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;TENANT_SUSPENDED&quot;,
        &quot;message&quot;: &quot;Your company account is suspended.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not found or belongs to another company):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;NOT_FOUND&quot;,
        &quot;message&quot;: &quot;The requested resource was not found.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;VALIDATION_FAILED&quot;,
        &quot;message&quot;: &quot;The given data was invalid.&quot;,
        &quot;details&quot;: {
            &quot;email&quot;: [
                &quot;The email has already been taken.&quot;
            ]
        }
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (429, Rate limit hit (Retry-After header is set)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;RATE_LIMITED&quot;,
        &quot;message&quot;: &quot;Too many requests. Please slow down.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
    </span>
<span id="execution-results-PATCHapi-v1-customers--customer_ulid-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PATCHapi-v1-customers--customer_ulid-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PATCHapi-v1-customers--customer_ulid-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PATCHapi-v1-customers--customer_ulid-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PATCHapi-v1-customers--customer_ulid-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PATCHapi-v1-customers--customer_ulid-" data-method="PATCH"
      data-path="api/v1/customers/{customer_ulid}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PATCHapi-v1-customers--customer_ulid-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PATCHapi-v1-customers--customer_ulid-"
                    onclick="tryItOut('PATCHapi-v1-customers--customer_ulid-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PATCHapi-v1-customers--customer_ulid-"
                    onclick="cancelTryOut('PATCHapi-v1-customers--customer_ulid-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PATCHapi-v1-customers--customer_ulid-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/v1/customers/{customer_ulid}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PATCHapi-v1-customers--customer_ulid-"
               value="Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PATCHapi-v1-customers--customer_ulid-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PATCHapi-v1-customers--customer_ulid-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>customer_ulid</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="customer_ulid"                data-endpoint="PATCHapi-v1-customers--customer_ulid-"
               value="architecto"
               data-component="url">
    <br>
<p>Example: <code>architecto</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PATCHapi-v1-customers--customer_ulid-"
               value="Karim Store"
               data-component="body">
    <br>
<p>Customer name. Must not be greater than 255 characters. Example: <code>Karim Store</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>email</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="email"                data-endpoint="PATCHapi-v1-customers--customer_ulid-"
               value="karim@store.test"
               data-component="body">
    <br>
<p>Unique within your company among non-deleted customers. Must be a valid email address. Must not be greater than 255 characters. Example: <code>karim@store.test</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>phone</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="phone"                data-endpoint="PATCHapi-v1-customers--customer_ulid-"
               value="+8801700000000"
               data-component="body">
    <br>
<p>Phone number. Send null to clear it. Must not be greater than 30 characters. Example: <code>+8801700000000</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>company_name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="company_name"                data-endpoint="PATCHapi-v1-customers--customer_ulid-"
               value="Karim Ltd"
               data-component="body">
    <br>
<p>Company name. Send null to clear it. Must not be greater than 255 characters. Example: <code>Karim Ltd</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="status"                data-endpoint="PATCHapi-v1-customers--customer_ulid-"
               value="inactive"
               data-component="body">
    <br>
<p>active or inactive. Example: <code>inactive</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>active</code></li> <li><code>inactive</code></li></ul>
        </div>
        </form>

                    <h2 id="customers-DELETEapi-v1-customers--customer_ulid-">Delete a customer</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Soft delete. The email can be used again. Needs customers.delete (members cannot).</p>

<span id="example-requests-DELETEapi-v1-customers--customer_ulid-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request DELETE \
    "http://localhost:8000/api/v1/customers/architecto" \
    --header "Authorization: Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>

</span>

<span id="example-responses-DELETEapi-v1-customers--customer_ulid-">
            <blockquote>
            <p>Example response (204, Deleted):</p>
        </blockquote>
                <pre>
<code>Empty response</code>
 </pre>
            <blockquote>
            <p>Example response (401, Missing, invalid, or expired token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;UNAUTHENTICATED&quot;,
        &quot;message&quot;: &quot;Unauthenticated.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (402, Subscription not usable (writes only)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;SUBSCRIPTION_INACTIVE&quot;,
        &quot;message&quot;: &quot;Your subscription is not active. Renew it to make changes.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, No permission, or wrong user type for this route):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;FORBIDDEN&quot;,
        &quot;message&quot;: &quot;This action is unauthorized.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Company suspended):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;TENANT_SUSPENDED&quot;,
        &quot;message&quot;: &quot;Your company account is suspended.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not found or belongs to another company):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;NOT_FOUND&quot;,
        &quot;message&quot;: &quot;The requested resource was not found.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (429, Rate limit hit (Retry-After header is set)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;RATE_LIMITED&quot;,
        &quot;message&quot;: &quot;Too many requests. Please slow down.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
    </span>
<span id="execution-results-DELETEapi-v1-customers--customer_ulid-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-DELETEapi-v1-customers--customer_ulid-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-DELETEapi-v1-customers--customer_ulid-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-DELETEapi-v1-customers--customer_ulid-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-DELETEapi-v1-customers--customer_ulid-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-DELETEapi-v1-customers--customer_ulid-" data-method="DELETE"
      data-path="api/v1/customers/{customer_ulid}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('DELETEapi-v1-customers--customer_ulid-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-DELETEapi-v1-customers--customer_ulid-"
                    onclick="tryItOut('DELETEapi-v1-customers--customer_ulid-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-DELETEapi-v1-customers--customer_ulid-"
                    onclick="cancelTryOut('DELETEapi-v1-customers--customer_ulid-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-DELETEapi-v1-customers--customer_ulid-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-red">DELETE</small>
            <b><code>api/v1/customers/{customer_ulid}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="DELETEapi-v1-customers--customer_ulid-"
               value="Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="DELETEapi-v1-customers--customer_ulid-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="DELETEapi-v1-customers--customer_ulid-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>customer_ulid</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="customer_ulid"                data-endpoint="DELETEapi-v1-customers--customer_ulid-"
               value="architecto"
               data-component="url">
    <br>
<p>Example: <code>architecto</code></p>
            </div>
                    </form>

                <h1 id="customer-exports">Customer Exports</h1>

    <p>Asynchronous CSV exports of customers.</p>

                                <h2 id="customer-exports-POSTapi-v1-customers-exports">Request a CSV export</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Takes the same filters as the customer list. A queued job builds the CSV; poll the export until download_url is set. Needs the customer_export plan feature and customers.export. Limited to 5 per hour per company.</p>

<span id="example-requests-POSTapi-v1-customers-exports">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/customers/exports?filter[status]=active&amp;filter[search]=b&amp;filter[created_from]=2026-10-02&amp;filter[created_to]=2052-10-25" \
    --header "Authorization: Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-customers-exports">
            <blockquote>
            <p>Example response (202):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: &quot;01m3yq2mzrzmdpxqgw955qbt99&quot;,
        &quot;status&quot;: &quot;pending&quot;,
        &quot;row_count&quot;: null,
        &quot;download_url&quot;: null,
        &quot;error_message&quot;: null,
        &quot;created_at&quot;: &quot;2026-10-02T16:27:37Z&quot;,
        &quot;completed_at&quot;: null
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Missing, invalid, or expired token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;UNAUTHENTICATED&quot;,
        &quot;message&quot;: &quot;Unauthenticated.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (402, Subscription not usable (writes only)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;SUBSCRIPTION_INACTIVE&quot;,
        &quot;message&quot;: &quot;Your subscription is not active. Renew it to make changes.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, No permission, or wrong user type for this route):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;FORBIDDEN&quot;,
        &quot;message&quot;: &quot;This action is unauthorized.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Company suspended):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;TENANT_SUSPENDED&quot;,
        &quot;message&quot;: &quot;Your company account is suspended.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Plan has no customer export):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;FEATURE_NOT_AVAILABLE&quot;,
        &quot;message&quot;: &quot;Your plan does not include this feature. Upgrade your plan to use it.&quot;,
        &quot;details&quot;: {
            &quot;feature&quot;: &quot;customer_export&quot;
        }
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;VALIDATION_FAILED&quot;,
        &quot;message&quot;: &quot;The given data was invalid.&quot;,
        &quot;details&quot;: {
            &quot;filter.status&quot;: [
                &quot;The selected filter.status is invalid.&quot;
            ]
        }
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (429, Rate limit hit (Retry-After header is set)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;RATE_LIMITED&quot;,
        &quot;message&quot;: &quot;Too many requests. Please slow down.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-customers-exports" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-customers-exports"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-customers-exports"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-customers-exports" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-customers-exports">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-customers-exports" data-method="POST"
      data-path="api/v1/customers/exports"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-customers-exports', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-customers-exports"
                    onclick="tryItOut('POSTapi-v1-customers-exports');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-customers-exports"
                    onclick="cancelTryOut('POSTapi-v1-customers-exports');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-customers-exports"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/customers/exports</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-customers-exports"
               value="Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-customers-exports"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-customers-exports"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>filter</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="filter"                data-endpoint="POSTapi-v1-customers-exports"
               value=""
               data-component="query">
    <br>

            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>filter.status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="filter.status"                data-endpoint="POSTapi-v1-customers-exports"
               value="active"
               data-component="query">
    <br>
<p>Example: <code>active</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>active</code></li> <li><code>inactive</code></li></ul>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>filter.search</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="filter.search"                data-endpoint="POSTapi-v1-customers-exports"
               value="b"
               data-component="query">
    <br>
<p>Must not be greater than 100 characters. Example: <code>b</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>filter.created_from</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="filter.created_from"                data-endpoint="POSTapi-v1-customers-exports"
               value="2026-10-02"
               data-component="query">
    <br>
<p>Must be a valid date in the format <code>Y-m-d</code>. Example: <code>2026-10-02</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>filter.created_to</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="filter.created_to"                data-endpoint="POSTapi-v1-customers-exports"
               value="2052-10-25"
               data-component="query">
    <br>
<p>Must be a valid date in the format <code>Y-m-d</code>. Must be a date after or equal to <code>filter.created_from</code>. Example: <code>2052-10-25</code></p>
            </div>
                </form>

                    <h2 id="customer-exports-GETapi-v1-customers-exports--export_ulid-">Show an export</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>download_url is null until the status is completed.</p>

<span id="example-requests-GETapi-v1-customers-exports--export_ulid-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/customers/exports/architecto" \
    --header "Authorization: Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-customers-exports--export_ulid-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: &quot;01m3yq2mzrzmdpxqgw955qbt99&quot;,
        &quot;status&quot;: &quot;completed&quot;,
        &quot;row_count&quot;: 181,
        &quot;download_url&quot;: &quot;http://localhost:8000/api/v1/customers/exports/01m3yq2mzrzmdpxqgw955qbt99/download&quot;,
        &quot;error_message&quot;: null,
        &quot;created_at&quot;: &quot;2026-10-02T16:27:37Z&quot;,
        &quot;completed_at&quot;: &quot;2026-10-02T16:27:38Z&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Missing, invalid, or expired token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;UNAUTHENTICATED&quot;,
        &quot;message&quot;: &quot;Unauthenticated.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, No permission, or wrong user type for this route):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;FORBIDDEN&quot;,
        &quot;message&quot;: &quot;This action is unauthorized.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Company suspended):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;TENANT_SUSPENDED&quot;,
        &quot;message&quot;: &quot;Your company account is suspended.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Plan has no customer export):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;FEATURE_NOT_AVAILABLE&quot;,
        &quot;message&quot;: &quot;Your plan does not include this feature. Upgrade your plan to use it.&quot;,
        &quot;details&quot;: {
            &quot;feature&quot;: &quot;customer_export&quot;
        }
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not found or belongs to another company):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;NOT_FOUND&quot;,
        &quot;message&quot;: &quot;The requested resource was not found.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (429, Rate limit hit (Retry-After header is set)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;RATE_LIMITED&quot;,
        &quot;message&quot;: &quot;Too many requests. Please slow down.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-customers-exports--export_ulid-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-customers-exports--export_ulid-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-customers-exports--export_ulid-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-customers-exports--export_ulid-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-customers-exports--export_ulid-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-customers-exports--export_ulid-" data-method="GET"
      data-path="api/v1/customers/exports/{export_ulid}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-customers-exports--export_ulid-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-customers-exports--export_ulid-"
                    onclick="tryItOut('GETapi-v1-customers-exports--export_ulid-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-customers-exports--export_ulid-"
                    onclick="cancelTryOut('GETapi-v1-customers-exports--export_ulid-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-customers-exports--export_ulid-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/customers/exports/{export_ulid}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-customers-exports--export_ulid-"
               value="Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-customers-exports--export_ulid-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-customers-exports--export_ulid-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>export_ulid</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="export_ulid"                data-endpoint="GETapi-v1-customers-exports--export_ulid-"
               value="architecto"
               data-component="url">
    <br>
<p>Example: <code>architecto</code></p>
            </div>
                    </form>

                    <h2 id="customer-exports-GETapi-v1-customers-exports--export_ulid--download">Download an export</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Streams the CSV file. Cells that start with =, +, -, or @ are prefixed with a quote, so spreadsheets do not run them as formulas.</p>

<span id="example-requests-GETapi-v1-customers-exports--export_ulid--download">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/customers/exports/architecto/download" \
    --header "Authorization: Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-customers-exports--export_ulid--download">
            <blockquote>
            <p>Example response (200, CSV file):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">&quot;id,name,email,phone,company_name,status,created_at\n01m3yq2mys56kee6prc9xsn2ac,\&quot;Karim Store\&quot;,karim@store.test,&#039;+8801700000000,\&quot;Karim Ltd\&quot;,active,2026-10-02T16:27:37Z&quot;</code>
 </pre>
            <blockquote>
            <p>Example response (401, Missing, invalid, or expired token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;UNAUTHENTICATED&quot;,
        &quot;message&quot;: &quot;Unauthenticated.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, No permission, or wrong user type for this route):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;FORBIDDEN&quot;,
        &quot;message&quot;: &quot;This action is unauthorized.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Company suspended):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;TENANT_SUSPENDED&quot;,
        &quot;message&quot;: &quot;Your company account is suspended.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Plan has no customer export):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;FEATURE_NOT_AVAILABLE&quot;,
        &quot;message&quot;: &quot;Your plan does not include this feature. Upgrade your plan to use it.&quot;,
        &quot;details&quot;: {
            &quot;feature&quot;: &quot;customer_export&quot;
        }
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not found or belongs to another company):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;NOT_FOUND&quot;,
        &quot;message&quot;: &quot;The requested resource was not found.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (409, Export not completed yet):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;EXPORT_NOT_READY&quot;,
        &quot;message&quot;: &quot;The export is not ready yet.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (429, Rate limit hit (Retry-After header is set)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;RATE_LIMITED&quot;,
        &quot;message&quot;: &quot;Too many requests. Please slow down.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-customers-exports--export_ulid--download" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-customers-exports--export_ulid--download"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-customers-exports--export_ulid--download"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-customers-exports--export_ulid--download" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-customers-exports--export_ulid--download">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-customers-exports--export_ulid--download" data-method="GET"
      data-path="api/v1/customers/exports/{export_ulid}/download"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-customers-exports--export_ulid--download', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-customers-exports--export_ulid--download"
                    onclick="tryItOut('GETapi-v1-customers-exports--export_ulid--download');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-customers-exports--export_ulid--download"
                    onclick="cancelTryOut('GETapi-v1-customers-exports--export_ulid--download');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-customers-exports--export_ulid--download"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/customers/exports/{export_ulid}/download</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-customers-exports--export_ulid--download"
               value="Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-customers-exports--export_ulid--download"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-customers-exports--export_ulid--download"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>export_ulid</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="export_ulid"                data-endpoint="GETapi-v1-customers-exports--export_ulid--download"
               value="architecto"
               data-component="url">
    <br>
<p>Example: <code>architecto</code></p>
            </div>
                    </form>

                <h1 id="plans">Plans</h1>

    <p>Public plan list.</p>

                                <h2 id="plans-GETapi-v1-plans">List plans</h2>

<p>
</p>

<p>Active plans with their features, in display order. Cached. Limited to 60 requests per minute per IP.</p>

<span id="example-requests-GETapi-v1-plans">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/plans" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-plans">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: &quot;01m3ypwrg7s3rw8thb4tw1226n&quot;,
            &quot;code&quot;: &quot;starter&quot;,
            &quot;name&quot;: &quot;Starter&quot;,
            &quot;price_cents&quot;: 1900,
            &quot;currency&quot;: &quot;USD&quot;,
            &quot;features&quot;: [
                {
                    &quot;key&quot;: &quot;max_users&quot;,
                    &quot;enabled&quot;: true,
                    &quot;limit&quot;: 5
                },
                {
                    &quot;key&quot;: &quot;max_customers&quot;,
                    &quot;enabled&quot;: true,
                    &quot;limit&quot;: 500
                },
                {
                    &quot;key&quot;: &quot;api_rate_per_minute&quot;,
                    &quot;enabled&quot;: true,
                    &quot;limit&quot;: 120
                },
                {
                    &quot;key&quot;: &quot;customer_export&quot;,
                    &quot;enabled&quot;: true,
                    &quot;limit&quot;: null
                },
                {
                    &quot;key&quot;: &quot;analytics_trends&quot;,
                    &quot;enabled&quot;: false,
                    &quot;limit&quot;: null
                }
            ]
        },
        {
            &quot;id&quot;: &quot;01m3ypwrhqvabc00wv89eb5qez&quot;,
            &quot;code&quot;: &quot;pro&quot;,
            &quot;name&quot;: &quot;Pro&quot;,
            &quot;price_cents&quot;: 4900,
            &quot;currency&quot;: &quot;USD&quot;,
            &quot;features&quot;: [
                {
                    &quot;key&quot;: &quot;max_users&quot;,
                    &quot;enabled&quot;: true,
                    &quot;limit&quot;: 25
                },
                {
                    &quot;key&quot;: &quot;max_customers&quot;,
                    &quot;enabled&quot;: true,
                    &quot;limit&quot;: 5000
                },
                {
                    &quot;key&quot;: &quot;api_rate_per_minute&quot;,
                    &quot;enabled&quot;: true,
                    &quot;limit&quot;: 600
                },
                {
                    &quot;key&quot;: &quot;customer_export&quot;,
                    &quot;enabled&quot;: true,
                    &quot;limit&quot;: null
                },
                {
                    &quot;key&quot;: &quot;analytics_trends&quot;,
                    &quot;enabled&quot;: true,
                    &quot;limit&quot;: null
                }
            ]
        }
    ]
}</code>
 </pre>
            <blockquote>
            <p>Example response (429, Rate limit hit (Retry-After header is set)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;RATE_LIMITED&quot;,
        &quot;message&quot;: &quot;Too many requests. Please slow down.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-plans" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-plans"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-plans"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-plans" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-plans">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-plans" data-method="GET"
      data-path="api/v1/plans"
      data-authed="0"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-plans', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-plans"
                    onclick="tryItOut('GETapi-v1-plans');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-plans"
                    onclick="cancelTryOut('GETapi-v1-plans');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-plans"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/plans</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-plans"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-plans"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                <h1 id="subscription">Subscription</h1>

    <p>Your company subscription. There is no payment gateway; renew simulates a successful payment.</p>

                                <h2 id="subscription-GETapi-v1-subscription">Show the subscription</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Plan, status, dates, features, and usage against the plan limits. Needs subscription.view.</p>

<span id="example-requests-GETapi-v1-subscription">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/subscription" \
    --header "Authorization: Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-subscription">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: &quot;01m3ypws7te03dc3frgev51ras&quot;,
        &quot;status&quot;: &quot;active&quot;,
        &quot;plan&quot;: {
            &quot;id&quot;: &quot;01m3ypwrhqvabc00wv89eb5qez&quot;,
            &quot;code&quot;: &quot;pro&quot;,
            &quot;name&quot;: &quot;Pro&quot;,
            &quot;price_cents&quot;: 4900,
            &quot;currency&quot;: &quot;USD&quot;,
            &quot;features&quot;: [
                {
                    &quot;key&quot;: &quot;max_users&quot;,
                    &quot;enabled&quot;: true,
                    &quot;limit&quot;: 25
                },
                {
                    &quot;key&quot;: &quot;max_customers&quot;,
                    &quot;enabled&quot;: true,
                    &quot;limit&quot;: 5000
                },
                {
                    &quot;key&quot;: &quot;api_rate_per_minute&quot;,
                    &quot;enabled&quot;: true,
                    &quot;limit&quot;: 600
                },
                {
                    &quot;key&quot;: &quot;customer_export&quot;,
                    &quot;enabled&quot;: true,
                    &quot;limit&quot;: null
                },
                {
                    &quot;key&quot;: &quot;analytics_trends&quot;,
                    &quot;enabled&quot;: true,
                    &quot;limit&quot;: null
                }
            ]
        },
        &quot;trial_ends_at&quot;: null,
        &quot;current_period_start&quot;: &quot;2026-10-02T16:24:25Z&quot;,
        &quot;current_period_end&quot;: &quot;2026-11-02T16:24:25Z&quot;,
        &quot;cancelled_at&quot;: null,
        &quot;ends_at&quot;: null,
        &quot;is_usable&quot;: true,
        &quot;usage&quot;: {
            &quot;users&quot;: {
                &quot;used&quot;: 3,
                &quot;limit&quot;: 25,
                &quot;over_limit&quot;: false
            },
            &quot;customers&quot;: {
                &quot;used&quot;: 200,
                &quot;limit&quot;: 5000,
                &quot;over_limit&quot;: false
            }
        }
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Missing, invalid, or expired token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;UNAUTHENTICATED&quot;,
        &quot;message&quot;: &quot;Unauthenticated.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, No permission, or wrong user type for this route):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;FORBIDDEN&quot;,
        &quot;message&quot;: &quot;This action is unauthorized.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Company suspended):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;TENANT_SUSPENDED&quot;,
        &quot;message&quot;: &quot;Your company account is suspended.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (429, Rate limit hit (Retry-After header is set)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;RATE_LIMITED&quot;,
        &quot;message&quot;: &quot;Too many requests. Please slow down.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-subscription" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-subscription"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-subscription"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-subscription" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-subscription">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-subscription" data-method="GET"
      data-path="api/v1/subscription"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-subscription', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-subscription"
                    onclick="tryItOut('GETapi-v1-subscription');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-subscription"
                    onclick="cancelTryOut('GETapi-v1-subscription');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-subscription"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/subscription</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-subscription"
               value="Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-subscription"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-subscription"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="subscription-POSTapi-v1-subscription-change-plan">Change plan</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Switches between plans and keeps the status and dates. Moving to free makes the subscription active with no period. A free subscription must use renew to start a paid plan. Downgrading over a limit is allowed; new additions are blocked. Owner only.</p>

<span id="example-requests-POSTapi-v1-subscription-change-plan">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/subscription/change-plan" \
    --header "Authorization: Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"plan_code\": \"pro\"
}"
</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-subscription-change-plan">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: &quot;01m3ypws7te03dc3frgev51ras&quot;,
        &quot;status&quot;: &quot;active&quot;,
        &quot;plan&quot;: {
            &quot;id&quot;: &quot;01m3ypwrhqvabc00wv89eb5qez&quot;,
            &quot;code&quot;: &quot;pro&quot;,
            &quot;name&quot;: &quot;Pro&quot;,
            &quot;price_cents&quot;: 4900,
            &quot;currency&quot;: &quot;USD&quot;,
            &quot;features&quot;: [
                {
                    &quot;key&quot;: &quot;max_users&quot;,
                    &quot;enabled&quot;: true,
                    &quot;limit&quot;: 25
                },
                {
                    &quot;key&quot;: &quot;max_customers&quot;,
                    &quot;enabled&quot;: true,
                    &quot;limit&quot;: 5000
                },
                {
                    &quot;key&quot;: &quot;api_rate_per_minute&quot;,
                    &quot;enabled&quot;: true,
                    &quot;limit&quot;: 600
                },
                {
                    &quot;key&quot;: &quot;customer_export&quot;,
                    &quot;enabled&quot;: true,
                    &quot;limit&quot;: null
                },
                {
                    &quot;key&quot;: &quot;analytics_trends&quot;,
                    &quot;enabled&quot;: true,
                    &quot;limit&quot;: null
                }
            ]
        },
        &quot;trial_ends_at&quot;: null,
        &quot;current_period_start&quot;: &quot;2026-10-02T16:24:25Z&quot;,
        &quot;current_period_end&quot;: &quot;2026-11-02T16:24:25Z&quot;,
        &quot;cancelled_at&quot;: null,
        &quot;ends_at&quot;: null,
        &quot;is_usable&quot;: true,
        &quot;usage&quot;: {
            &quot;users&quot;: {
                &quot;used&quot;: 3,
                &quot;limit&quot;: 25,
                &quot;over_limit&quot;: false
            },
            &quot;customers&quot;: {
                &quot;used&quot;: 200,
                &quot;limit&quot;: 5000,
                &quot;over_limit&quot;: false
            }
        }
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Missing, invalid, or expired token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;UNAUTHENTICATED&quot;,
        &quot;message&quot;: &quot;Unauthenticated.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, No permission, or wrong user type for this route):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;FORBIDDEN&quot;,
        &quot;message&quot;: &quot;This action is unauthorized.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Company suspended):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;TENANT_SUSPENDED&quot;,
        &quot;message&quot;: &quot;Your company account is suspended.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (409, Change not allowed from the current state):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;INVALID_SUBSCRIPTION_TRANSITION&quot;,
        &quot;message&quot;: &quot;Use renew to start a paid plan.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;VALIDATION_FAILED&quot;,
        &quot;message&quot;: &quot;The given data was invalid.&quot;,
        &quot;details&quot;: {
            &quot;plan_code&quot;: [
                &quot;The selected plan code is invalid.&quot;
            ]
        }
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (429, Rate limit hit (Retry-After header is set)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;RATE_LIMITED&quot;,
        &quot;message&quot;: &quot;Too many requests. Please slow down.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-subscription-change-plan" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-subscription-change-plan"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-subscription-change-plan"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-subscription-change-plan" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-subscription-change-plan">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-subscription-change-plan" data-method="POST"
      data-path="api/v1/subscription/change-plan"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-subscription-change-plan', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-subscription-change-plan"
                    onclick="tryItOut('POSTapi-v1-subscription-change-plan');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-subscription-change-plan"
                    onclick="cancelTryOut('POSTapi-v1-subscription-change-plan');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-subscription-change-plan"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/subscription/change-plan</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-subscription-change-plan"
               value="Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-subscription-change-plan"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-subscription-change-plan"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>plan_code</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="plan_code"                data-endpoint="POSTapi-v1-subscription-change-plan"
               value="pro"
               data-component="body">
    <br>
<p>An active plan code. Moving to free makes the subscription active with no period. Must match an existing stored value. Example: <code>pro</code></p>
        </div>
        </form>

                    <h2 id="subscription-POSTapi-v1-subscription-renew">Renew (simulated payment)</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Payment stub. Sets the status to active with a one-month period from now, optionally on another paid plan. Works from trialing, past_due, cancelled, expired, or free. Owner only.</p>

<span id="example-requests-POSTapi-v1-subscription-renew">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/subscription/renew" \
    --header "Authorization: Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"plan_code\": \"pro\"
}"
</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-subscription-renew">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: &quot;01m3ypws7te03dc3frgev51ras&quot;,
        &quot;status&quot;: &quot;active&quot;,
        &quot;plan&quot;: {
            &quot;id&quot;: &quot;01m3ypwrhqvabc00wv89eb5qez&quot;,
            &quot;code&quot;: &quot;pro&quot;,
            &quot;name&quot;: &quot;Pro&quot;,
            &quot;price_cents&quot;: 4900,
            &quot;currency&quot;: &quot;USD&quot;,
            &quot;features&quot;: [
                {
                    &quot;key&quot;: &quot;max_users&quot;,
                    &quot;enabled&quot;: true,
                    &quot;limit&quot;: 25
                },
                {
                    &quot;key&quot;: &quot;max_customers&quot;,
                    &quot;enabled&quot;: true,
                    &quot;limit&quot;: 5000
                },
                {
                    &quot;key&quot;: &quot;api_rate_per_minute&quot;,
                    &quot;enabled&quot;: true,
                    &quot;limit&quot;: 600
                },
                {
                    &quot;key&quot;: &quot;customer_export&quot;,
                    &quot;enabled&quot;: true,
                    &quot;limit&quot;: null
                },
                {
                    &quot;key&quot;: &quot;analytics_trends&quot;,
                    &quot;enabled&quot;: true,
                    &quot;limit&quot;: null
                }
            ]
        },
        &quot;trial_ends_at&quot;: null,
        &quot;current_period_start&quot;: &quot;2026-10-02T16:24:25Z&quot;,
        &quot;current_period_end&quot;: &quot;2026-11-02T16:24:25Z&quot;,
        &quot;cancelled_at&quot;: null,
        &quot;ends_at&quot;: null,
        &quot;is_usable&quot;: true,
        &quot;usage&quot;: {
            &quot;users&quot;: {
                &quot;used&quot;: 3,
                &quot;limit&quot;: 25,
                &quot;over_limit&quot;: false
            },
            &quot;customers&quot;: {
                &quot;used&quot;: 200,
                &quot;limit&quot;: 5000,
                &quot;over_limit&quot;: false
            }
        }
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Missing, invalid, or expired token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;UNAUTHENTICATED&quot;,
        &quot;message&quot;: &quot;Unauthenticated.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, No permission, or wrong user type for this route):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;FORBIDDEN&quot;,
        &quot;message&quot;: &quot;This action is unauthorized.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Company suspended):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;TENANT_SUSPENDED&quot;,
        &quot;message&quot;: &quot;Your company account is suspended.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (409, Change not allowed from the current state):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;INVALID_SUBSCRIPTION_TRANSITION&quot;,
        &quot;message&quot;: &quot;The subscription is already active.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;VALIDATION_FAILED&quot;,
        &quot;message&quot;: &quot;The given data was invalid.&quot;,
        &quot;details&quot;: {
            &quot;plan_code&quot;: [
                &quot;The selected plan code is invalid.&quot;
            ]
        }
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (429, Rate limit hit (Retry-After header is set)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;RATE_LIMITED&quot;,
        &quot;message&quot;: &quot;Too many requests. Please slow down.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-subscription-renew" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-subscription-renew"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-subscription-renew"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-subscription-renew" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-subscription-renew">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-subscription-renew" data-method="POST"
      data-path="api/v1/subscription/renew"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-subscription-renew', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-subscription-renew"
                    onclick="tryItOut('POSTapi-v1-subscription-renew');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-subscription-renew"
                    onclick="cancelTryOut('POSTapi-v1-subscription-renew');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-subscription-renew"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/subscription/renew</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-subscription-renew"
               value="Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-subscription-renew"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-subscription-renew"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>plan_code</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="plan_code"                data-endpoint="POSTapi-v1-subscription-renew"
               value="pro"
               data-component="body">
    <br>
<p>Optional active paid plan code to switch to while renewing. Defaults to the current plan. Must match an existing stored value. Example: <code>pro</code></p>
        </div>
        </form>

                    <h2 id="subscription-POSTapi-v1-subscription-cancel">Cancel</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Access continues until the trial or period end (immediately when past_due). A free plan cannot be cancelled. Owner only.</p>

<span id="example-requests-POSTapi-v1-subscription-cancel">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/subscription/cancel" \
    --header "Authorization: Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-subscription-cancel">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: &quot;01m3ypws7te03dc3frgev51ras&quot;,
        &quot;status&quot;: &quot;cancelled&quot;,
        &quot;plan&quot;: {
            &quot;id&quot;: &quot;01m3ypwrhqvabc00wv89eb5qez&quot;,
            &quot;code&quot;: &quot;pro&quot;,
            &quot;name&quot;: &quot;Pro&quot;,
            &quot;price_cents&quot;: 4900,
            &quot;currency&quot;: &quot;USD&quot;,
            &quot;features&quot;: [
                {
                    &quot;key&quot;: &quot;max_users&quot;,
                    &quot;enabled&quot;: true,
                    &quot;limit&quot;: 25
                },
                {
                    &quot;key&quot;: &quot;max_customers&quot;,
                    &quot;enabled&quot;: true,
                    &quot;limit&quot;: 5000
                },
                {
                    &quot;key&quot;: &quot;api_rate_per_minute&quot;,
                    &quot;enabled&quot;: true,
                    &quot;limit&quot;: 600
                },
                {
                    &quot;key&quot;: &quot;customer_export&quot;,
                    &quot;enabled&quot;: true,
                    &quot;limit&quot;: null
                },
                {
                    &quot;key&quot;: &quot;analytics_trends&quot;,
                    &quot;enabled&quot;: true,
                    &quot;limit&quot;: null
                }
            ]
        },
        &quot;trial_ends_at&quot;: null,
        &quot;current_period_start&quot;: &quot;2026-10-02T16:24:25Z&quot;,
        &quot;current_period_end&quot;: &quot;2026-11-02T16:24:25Z&quot;,
        &quot;cancelled_at&quot;: &quot;2026-10-03T09:00:00Z&quot;,
        &quot;ends_at&quot;: &quot;2026-11-02T16:24:25Z&quot;,
        &quot;is_usable&quot;: true,
        &quot;usage&quot;: {
            &quot;users&quot;: {
                &quot;used&quot;: 3,
                &quot;limit&quot;: 25,
                &quot;over_limit&quot;: false
            },
            &quot;customers&quot;: {
                &quot;used&quot;: 200,
                &quot;limit&quot;: 5000,
                &quot;over_limit&quot;: false
            }
        }
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Missing, invalid, or expired token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;UNAUTHENTICATED&quot;,
        &quot;message&quot;: &quot;Unauthenticated.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, No permission, or wrong user type for this route):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;FORBIDDEN&quot;,
        &quot;message&quot;: &quot;This action is unauthorized.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Company suspended):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;TENANT_SUSPENDED&quot;,
        &quot;message&quot;: &quot;Your company account is suspended.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (409, Change not allowed from the current state):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;INVALID_SUBSCRIPTION_TRANSITION&quot;,
        &quot;message&quot;: &quot;A free plan cannot be cancelled.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (429, Rate limit hit (Retry-After header is set)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;RATE_LIMITED&quot;,
        &quot;message&quot;: &quot;Too many requests. Please slow down.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-subscription-cancel" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-subscription-cancel"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-subscription-cancel"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-subscription-cancel" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-subscription-cancel">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-subscription-cancel" data-method="POST"
      data-path="api/v1/subscription/cancel"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-subscription-cancel', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-subscription-cancel"
                    onclick="tryItOut('POSTapi-v1-subscription-cancel');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-subscription-cancel"
                    onclick="cancelTryOut('POSTapi-v1-subscription-cancel');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-subscription-cancel"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/subscription/cancel</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-subscription-cancel"
               value="Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-subscription-cancel"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-subscription-cancel"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                <h1 id="dashboard">Dashboard</h1>

    <p>Usage and analytics.</p>

                                <h2 id="dashboard-GETapi-v1-dashboard">Company dashboard</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Subscription, usage against limits, customer counts, and a 30-day trend. trend is null when the plan lacks analytics_trends. Cached for 10 minutes and refreshed on any company data change.</p>

<span id="example-requests-GETapi-v1-dashboard">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/dashboard" \
    --header "Authorization: Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-dashboard">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;subscription&quot;: {
            &quot;status&quot;: &quot;active&quot;,
            &quot;plan&quot;: &quot;pro&quot;,
            &quot;current_period_end&quot;: &quot;2026-11-02T16:24:25Z&quot;,
            &quot;days_left&quot;: 31
        },
        &quot;usage&quot;: {
            &quot;users&quot;: {
                &quot;used&quot;: 3,
                &quot;limit&quot;: 25,
                &quot;over_limit&quot;: false
            },
            &quot;customers&quot;: {
                &quot;used&quot;: 200,
                &quot;limit&quot;: 5000,
                &quot;over_limit&quot;: false
            }
        },
        &quot;customers&quot;: {
            &quot;total&quot;: 200,
            &quot;active&quot;: 180,
            &quot;inactive&quot;: 20,
            &quot;new_last_30_days&quot;: 69
        },
        &quot;trend&quot;: [
            {
                &quot;date&quot;: &quot;2026-09-03&quot;,
                &quot;users&quot;: 3,
                &quot;customers&quot;: 156
            },
            {
                &quot;date&quot;: &quot;2026-09-04&quot;,
                &quot;users&quot;: 3,
                &quot;customers&quot;: 158
            }
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Missing, invalid, or expired token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;UNAUTHENTICATED&quot;,
        &quot;message&quot;: &quot;Unauthenticated.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, No permission, or wrong user type for this route):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;FORBIDDEN&quot;,
        &quot;message&quot;: &quot;This action is unauthorized.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, Company suspended):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;TENANT_SUSPENDED&quot;,
        &quot;message&quot;: &quot;Your company account is suspended.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (429, Rate limit hit (Retry-After header is set)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;RATE_LIMITED&quot;,
        &quot;message&quot;: &quot;Too many requests. Please slow down.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-dashboard" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-dashboard"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-dashboard"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-dashboard" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-dashboard">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-dashboard" data-method="GET"
      data-path="api/v1/dashboard"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-dashboard', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-dashboard"
                    onclick="tryItOut('GETapi-v1-dashboard');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-dashboard"
                    onclick="cancelTryOut('GETapi-v1-dashboard');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-dashboard"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/dashboard</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-dashboard"
               value="Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-dashboard"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-dashboard"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                <h1 id="admin">Admin</h1>

    <p>Platform admin endpoints.</p>
<p>Access is enforced by the platform.admin middleware on the admin route group.</p>

                                <h2 id="admin-GETapi-v1-admin-plans">List all plans</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Platform admin only. Includes inactive plans.</p>

<span id="example-requests-GETapi-v1-admin-plans">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/admin/plans" \
    --header "Authorization: Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-admin-plans">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: &quot;01m3ypwrg7s3rw8thb4tw1226n&quot;,
            &quot;code&quot;: &quot;starter&quot;,
            &quot;name&quot;: &quot;Starter&quot;,
            &quot;price_cents&quot;: 1900,
            &quot;currency&quot;: &quot;USD&quot;,
            &quot;features&quot;: [
                {
                    &quot;key&quot;: &quot;max_users&quot;,
                    &quot;enabled&quot;: true,
                    &quot;limit&quot;: 5
                },
                {
                    &quot;key&quot;: &quot;max_customers&quot;,
                    &quot;enabled&quot;: true,
                    &quot;limit&quot;: 500
                },
                {
                    &quot;key&quot;: &quot;api_rate_per_minute&quot;,
                    &quot;enabled&quot;: true,
                    &quot;limit&quot;: 120
                },
                {
                    &quot;key&quot;: &quot;customer_export&quot;,
                    &quot;enabled&quot;: true,
                    &quot;limit&quot;: null
                },
                {
                    &quot;key&quot;: &quot;analytics_trends&quot;,
                    &quot;enabled&quot;: false,
                    &quot;limit&quot;: null
                }
            ]
        },
        {
            &quot;id&quot;: &quot;01m3ypwrhqvabc00wv89eb5qez&quot;,
            &quot;code&quot;: &quot;pro&quot;,
            &quot;name&quot;: &quot;Pro&quot;,
            &quot;price_cents&quot;: 4900,
            &quot;currency&quot;: &quot;USD&quot;,
            &quot;features&quot;: [
                {
                    &quot;key&quot;: &quot;max_users&quot;,
                    &quot;enabled&quot;: true,
                    &quot;limit&quot;: 25
                },
                {
                    &quot;key&quot;: &quot;max_customers&quot;,
                    &quot;enabled&quot;: true,
                    &quot;limit&quot;: 5000
                },
                {
                    &quot;key&quot;: &quot;api_rate_per_minute&quot;,
                    &quot;enabled&quot;: true,
                    &quot;limit&quot;: 600
                },
                {
                    &quot;key&quot;: &quot;customer_export&quot;,
                    &quot;enabled&quot;: true,
                    &quot;limit&quot;: null
                },
                {
                    &quot;key&quot;: &quot;analytics_trends&quot;,
                    &quot;enabled&quot;: true,
                    &quot;limit&quot;: null
                }
            ]
        }
    ]
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Missing, invalid, or expired token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;UNAUTHENTICATED&quot;,
        &quot;message&quot;: &quot;Unauthenticated.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, No permission, or wrong user type for this route):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;FORBIDDEN&quot;,
        &quot;message&quot;: &quot;This action is unauthorized.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (429, Rate limit hit (Retry-After header is set)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;RATE_LIMITED&quot;,
        &quot;message&quot;: &quot;Too many requests. Please slow down.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-admin-plans" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-admin-plans"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-admin-plans"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-admin-plans" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-admin-plans">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-admin-plans" data-method="GET"
      data-path="api/v1/admin/plans"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-admin-plans', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-admin-plans"
                    onclick="tryItOut('GETapi-v1-admin-plans');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-admin-plans"
                    onclick="cancelTryOut('GETapi-v1-admin-plans');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-admin-plans"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/admin/plans</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-admin-plans"
               value="Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-admin-plans"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-admin-plans"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

                    <h2 id="admin-POSTapi-v1-admin-plans">Create a plan</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Platform admin only.</p>

<span id="example-requests-POSTapi-v1-admin-plans">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/admin/plans" \
    --header "Authorization: Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"code\": \"business\",
    \"name\": \"Business\",
    \"price_cents\": 9900,
    \"currency\": \"USD\",
    \"is_active\": true,
    \"sort_order\": 4,
    \"features\": [
        {
            \"key\": \"max_users\",
            \"enabled\": true,
            \"limit\": 50
        },
        {
            \"key\": \"customer_export\",
            \"enabled\": true,
            \"limit\": null
        }
    ]
}"
</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-admin-plans">
            <blockquote>
            <p>Example response (201):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: &quot;01m3ypwrhqvabc00wv89eb5qez&quot;,
        &quot;code&quot;: &quot;business&quot;,
        &quot;name&quot;: &quot;Business&quot;,
        &quot;price_cents&quot;: 4900,
        &quot;currency&quot;: &quot;USD&quot;,
        &quot;features&quot;: [
            {
                &quot;key&quot;: &quot;max_users&quot;,
                &quot;enabled&quot;: true,
                &quot;limit&quot;: 25
            },
            {
                &quot;key&quot;: &quot;max_customers&quot;,
                &quot;enabled&quot;: true,
                &quot;limit&quot;: 5000
            },
            {
                &quot;key&quot;: &quot;api_rate_per_minute&quot;,
                &quot;enabled&quot;: true,
                &quot;limit&quot;: 600
            },
            {
                &quot;key&quot;: &quot;customer_export&quot;,
                &quot;enabled&quot;: true,
                &quot;limit&quot;: null
            },
            {
                &quot;key&quot;: &quot;analytics_trends&quot;,
                &quot;enabled&quot;: true,
                &quot;limit&quot;: null
            }
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Missing, invalid, or expired token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;UNAUTHENTICATED&quot;,
        &quot;message&quot;: &quot;Unauthenticated.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, No permission, or wrong user type for this route):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;FORBIDDEN&quot;,
        &quot;message&quot;: &quot;This action is unauthorized.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;VALIDATION_FAILED&quot;,
        &quot;message&quot;: &quot;The given data was invalid.&quot;,
        &quot;details&quot;: {
            &quot;code&quot;: [
                &quot;The code has already been taken.&quot;
            ]
        }
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (429, Rate limit hit (Retry-After header is set)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;RATE_LIMITED&quot;,
        &quot;message&quot;: &quot;Too many requests. Please slow down.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-admin-plans" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-admin-plans"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-admin-plans"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-admin-plans" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-admin-plans">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-admin-plans" data-method="POST"
      data-path="api/v1/admin/plans"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-admin-plans', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-admin-plans"
                    onclick="tryItOut('POSTapi-v1-admin-plans');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-admin-plans"
                    onclick="cancelTryOut('POSTapi-v1-admin-plans');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-admin-plans"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/admin/plans</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-admin-plans"
               value="Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-admin-plans"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-admin-plans"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>code</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="code"                data-endpoint="POSTapi-v1-admin-plans"
               value="business"
               data-component="body">
    <br>
<p>Unique plan code used in API requests. Letters, numbers, dashes, underscores. Must contain only letters, numbers, dashes and underscores. Must not be greater than 50 characters. Example: <code>business</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="POSTapi-v1-admin-plans"
               value="Business"
               data-component="body">
    <br>
<p>Display name. Must not be greater than 100 characters. Example: <code>Business</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>price_cents</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="price_cents"                data-endpoint="POSTapi-v1-admin-plans"
               value="9900"
               data-component="body">
    <br>
<p>Monthly price in cents. 0 makes it a free plan. Must be at least 0. Must not be greater than 4294967295. Example: <code>9900</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>currency</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="currency"                data-endpoint="POSTapi-v1-admin-plans"
               value="USD"
               data-component="body">
    <br>
<p>ISO 4217 code, uppercase. Must be 3 characters. Example: <code>USD</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_active</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="POSTapi-v1-admin-plans" style="display: none">
            <input type="radio" name="is_active"
                   value="true"
                   data-endpoint="POSTapi-v1-admin-plans"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-v1-admin-plans" style="display: none">
            <input type="radio" name="is_active"
                   value="false"
                   data-endpoint="POSTapi-v1-admin-plans"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Inactive plans are hidden and cannot be chosen. Default true. Example: <code>true</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>sort_order</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="sort_order"                data-endpoint="POSTapi-v1-admin-plans"
               value="4"
               data-component="body">
    <br>
<p>Display order. Default 0. Must be at least 0. Must not be greater than 65535. Example: <code>4</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>features</code></b>&nbsp;&nbsp;
<small>object[]</small>&nbsp;
 &nbsp;
 &nbsp;
<br>
<p>The plan features. Replaces all existing features of the plan. Must have at least 1 items.</p>
            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>key</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="features.0.key"                data-endpoint="POSTapi-v1-admin-plans"
               value="api_rate_per_minute"
               data-component="body">
    <br>
<p>Example: <code>api_rate_per_minute</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>max_users</code></li> <li><code>max_customers</code></li> <li><code>api_rate_per_minute</code></li> <li><code>customer_export</code></li> <li><code>analytics_trends</code></li></ul>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>enabled</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
 &nbsp;
 &nbsp;
                <label data-endpoint="POSTapi-v1-admin-plans" style="display: none">
            <input type="radio" name="features.0.enabled"
                   value="true"
                   data-endpoint="POSTapi-v1-admin-plans"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="POSTapi-v1-admin-plans" style="display: none">
            <input type="radio" name="features.0.enabled"
                   value="false"
                   data-endpoint="POSTapi-v1-admin-plans"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Example: <code>false</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>limit</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="features.0.limit"                data-endpoint="POSTapi-v1-admin-plans"
               value="1"
               data-component="body">
    <br>
<p>Must be at least 0. Must not be greater than 4294967295. Example: <code>1</code></p>
                    </div>
                                    </details>
        </div>
        </form>

                    <h2 id="admin-PATCHapi-v1-admin-plans--plan_ulid-">Update a plan</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Platform admin only. All fields optional; code cannot change. features, when sent, replaces all feature rows. Every company on the plan sees new limits on its next request.</p>

<span id="example-requests-PATCHapi-v1-admin-plans--plan_ulid-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request PATCH \
    "http://localhost:8000/api/v1/admin/plans/01m3yqq1kshr2g2mbf7r4ne31c" \
    --header "Authorization: Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json" \
    --data "{
    \"name\": \"Business\",
    \"price_cents\": 9900,
    \"currency\": \"USD\",
    \"is_active\": true,
    \"sort_order\": 4,
    \"features\": [
        {
            \"key\": \"max_users\",
            \"enabled\": true,
            \"limit\": 50
        },
        {
            \"key\": \"customer_export\",
            \"enabled\": true,
            \"limit\": null
        }
    ]
}"
</code></pre></div>

</span>

<span id="example-responses-PATCHapi-v1-admin-plans--plan_ulid-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: &quot;01m3ypwrhqvabc00wv89eb5qez&quot;,
        &quot;code&quot;: &quot;pro&quot;,
        &quot;name&quot;: &quot;Pro&quot;,
        &quot;price_cents&quot;: 4900,
        &quot;currency&quot;: &quot;USD&quot;,
        &quot;features&quot;: [
            {
                &quot;key&quot;: &quot;max_users&quot;,
                &quot;enabled&quot;: true,
                &quot;limit&quot;: 25
            },
            {
                &quot;key&quot;: &quot;max_customers&quot;,
                &quot;enabled&quot;: true,
                &quot;limit&quot;: 5000
            },
            {
                &quot;key&quot;: &quot;api_rate_per_minute&quot;,
                &quot;enabled&quot;: true,
                &quot;limit&quot;: 600
            },
            {
                &quot;key&quot;: &quot;customer_export&quot;,
                &quot;enabled&quot;: true,
                &quot;limit&quot;: null
            },
            {
                &quot;key&quot;: &quot;analytics_trends&quot;,
                &quot;enabled&quot;: true,
                &quot;limit&quot;: null
            }
        ]
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Missing, invalid, or expired token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;UNAUTHENTICATED&quot;,
        &quot;message&quot;: &quot;Unauthenticated.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, No permission, or wrong user type for this route):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;FORBIDDEN&quot;,
        &quot;message&quot;: &quot;This action is unauthorized.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not found or belongs to another company):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;NOT_FOUND&quot;,
        &quot;message&quot;: &quot;The requested resource was not found.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;VALIDATION_FAILED&quot;,
        &quot;message&quot;: &quot;The given data was invalid.&quot;,
        &quot;details&quot;: {
            &quot;code&quot;: [
                &quot;The code field is prohibited.&quot;
            ]
        }
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (429, Rate limit hit (Retry-After header is set)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;RATE_LIMITED&quot;,
        &quot;message&quot;: &quot;Too many requests. Please slow down.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
    </span>
<span id="execution-results-PATCHapi-v1-admin-plans--plan_ulid-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-PATCHapi-v1-admin-plans--plan_ulid-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-PATCHapi-v1-admin-plans--plan_ulid-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-PATCHapi-v1-admin-plans--plan_ulid-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-PATCHapi-v1-admin-plans--plan_ulid-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-PATCHapi-v1-admin-plans--plan_ulid-" data-method="PATCH"
      data-path="api/v1/admin/plans/{plan_ulid}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('PATCHapi-v1-admin-plans--plan_ulid-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-PATCHapi-v1-admin-plans--plan_ulid-"
                    onclick="tryItOut('PATCHapi-v1-admin-plans--plan_ulid-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-PATCHapi-v1-admin-plans--plan_ulid-"
                    onclick="cancelTryOut('PATCHapi-v1-admin-plans--plan_ulid-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-PATCHapi-v1-admin-plans--plan_ulid-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-purple">PATCH</small>
            <b><code>api/v1/admin/plans/{plan_ulid}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="PATCHapi-v1-admin-plans--plan_ulid-"
               value="Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="PATCHapi-v1-admin-plans--plan_ulid-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="PATCHapi-v1-admin-plans--plan_ulid-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>plan_ulid</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="plan_ulid"                data-endpoint="PATCHapi-v1-admin-plans--plan_ulid-"
               value="01m3yqq1kshr2g2mbf7r4ne31c"
               data-component="url">
    <br>
<p>Example: <code>01m3yqq1kshr2g2mbf7r4ne31c</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Body Parameters</b></h4>
        <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>code</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="code"                data-endpoint="PATCHapi-v1-admin-plans--plan_ulid-"
               value=""
               data-component="body">
    <br>

        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>name</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="name"                data-endpoint="PATCHapi-v1-admin-plans--plan_ulid-"
               value="Business"
               data-component="body">
    <br>
<p>Display name. Must not be greater than 100 characters. Example: <code>Business</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>price_cents</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="price_cents"                data-endpoint="PATCHapi-v1-admin-plans--plan_ulid-"
               value="9900"
               data-component="body">
    <br>
<p>Monthly price in cents. 0 makes it a free plan. Must be at least 0. Must not be greater than 4294967295. Example: <code>9900</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>currency</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="currency"                data-endpoint="PATCHapi-v1-admin-plans--plan_ulid-"
               value="USD"
               data-component="body">
    <br>
<p>ISO 4217 code, uppercase. Must be 3 characters. Example: <code>USD</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>is_active</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <label data-endpoint="PATCHapi-v1-admin-plans--plan_ulid-" style="display: none">
            <input type="radio" name="is_active"
                   value="true"
                   data-endpoint="PATCHapi-v1-admin-plans--plan_ulid-"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="PATCHapi-v1-admin-plans--plan_ulid-" style="display: none">
            <input type="radio" name="is_active"
                   value="false"
                   data-endpoint="PATCHapi-v1-admin-plans--plan_ulid-"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Inactive plans are hidden and cannot be chosen. Default true. Example: <code>true</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
            <b style="line-height: 2;"><code>sort_order</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="sort_order"                data-endpoint="PATCHapi-v1-admin-plans--plan_ulid-"
               value="4"
               data-component="body">
    <br>
<p>Display order. Default 0. Must be at least 0. Must not be greater than 65535. Example: <code>4</code></p>
        </div>
                <div style=" padding-left: 28px;  clear: unset;">
        <details>
            <summary style="padding-bottom: 10px;">
                <b style="line-height: 2;"><code>features</code></b>&nbsp;&nbsp;
<small>object[]</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
<br>
<p>The plan features. Replaces all existing features of the plan. Must have at least 1 items.</p>
            </summary>
                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>key</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="features.0.key"                data-endpoint="PATCHapi-v1-admin-plans--plan_ulid-"
               value="max_users"
               data-component="body">
    <br>
<p>Example: <code>max_users</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>max_users</code></li> <li><code>max_customers</code></li> <li><code>api_rate_per_minute</code></li> <li><code>customer_export</code></li> <li><code>analytics_trends</code></li></ul>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>enabled</code></b>&nbsp;&nbsp;
<small>boolean</small>&nbsp;
 &nbsp;
 &nbsp;
                <label data-endpoint="PATCHapi-v1-admin-plans--plan_ulid-" style="display: none">
            <input type="radio" name="features.0.enabled"
                   value="true"
                   data-endpoint="PATCHapi-v1-admin-plans--plan_ulid-"
                   data-component="body"             >
            <code>true</code>
        </label>
        <label data-endpoint="PATCHapi-v1-admin-plans--plan_ulid-" style="display: none">
            <input type="radio" name="features.0.enabled"
                   value="false"
                   data-endpoint="PATCHapi-v1-admin-plans--plan_ulid-"
                   data-component="body"             >
            <code>false</code>
        </label>
    <br>
<p>Example: <code>false</code></p>
                    </div>
                                                                <div style="margin-left: 14px; clear: unset;">
                        <b style="line-height: 2;"><code>limit</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="features.0.limit"                data-endpoint="PATCHapi-v1-admin-plans--plan_ulid-"
               value="1"
               data-component="body">
    <br>
<p>Must be at least 0. Must not be greater than 4294967295. Example: <code>1</code></p>
                    </div>
                                    </details>
        </div>
        </form>

                    <h2 id="admin-GETapi-v1-admin-tenants">List companies</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Platform admin only. Each company comes with its subscription and plan.</p>

<span id="example-requests-GETapi-v1-admin-tenants">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/admin/tenants?page=1&amp;per_page=15&amp;sort=name&amp;filter[status]=active&amp;filter[plan]=pro&amp;filter[search]=Acme" \
    --header "Authorization: Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-admin-tenants">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: [
        {
            &quot;id&quot;: &quot;01m3ypws0r7ercmyaecr9y5a12&quot;,
            &quot;name&quot;: &quot;Acme Ltd&quot;,
            &quot;status&quot;: &quot;active&quot;,
            &quot;created_at&quot;: &quot;2026-10-02T16:24:25Z&quot;,
            &quot;subscription&quot;: {
                &quot;id&quot;: &quot;01m3ypws7te03dc3frgev51ras&quot;,
                &quot;status&quot;: &quot;active&quot;,
                &quot;plan&quot;: {
                    &quot;id&quot;: &quot;01m3ypwrhqvabc00wv89eb5qez&quot;,
                    &quot;code&quot;: &quot;pro&quot;,
                    &quot;name&quot;: &quot;Pro&quot;,
                    &quot;price_cents&quot;: 4900,
                    &quot;currency&quot;: &quot;USD&quot;,
                    &quot;features&quot;: [
                        {
                            &quot;key&quot;: &quot;max_users&quot;,
                            &quot;enabled&quot;: true,
                            &quot;limit&quot;: 25
                        },
                        {
                            &quot;key&quot;: &quot;max_customers&quot;,
                            &quot;enabled&quot;: true,
                            &quot;limit&quot;: 5000
                        },
                        {
                            &quot;key&quot;: &quot;api_rate_per_minute&quot;,
                            &quot;enabled&quot;: true,
                            &quot;limit&quot;: 600
                        },
                        {
                            &quot;key&quot;: &quot;customer_export&quot;,
                            &quot;enabled&quot;: true,
                            &quot;limit&quot;: null
                        },
                        {
                            &quot;key&quot;: &quot;analytics_trends&quot;,
                            &quot;enabled&quot;: true,
                            &quot;limit&quot;: null
                        }
                    ]
                },
                &quot;trial_ends_at&quot;: null,
                &quot;current_period_start&quot;: &quot;2026-10-02T16:24:25Z&quot;,
                &quot;current_period_end&quot;: &quot;2026-11-02T16:24:25Z&quot;,
                &quot;cancelled_at&quot;: null,
                &quot;ends_at&quot;: null,
                &quot;is_usable&quot;: true
            }
        }
    ],
    &quot;links&quot;: {
        &quot;first&quot;: &quot;http://localhost:8000/api/v1/admin/tenants?page=1&quot;,
        &quot;last&quot;: &quot;http://localhost:8000/api/v1/admin/tenants?page=14&quot;,
        &quot;prev&quot;: null,
        &quot;next&quot;: &quot;http://localhost:8000/api/v1/admin/tenants?page=2&quot;
    },
    &quot;meta&quot;: {
        &quot;current_page&quot;: 1,
        &quot;from&quot;: 1,
        &quot;last_page&quot;: 14,
        &quot;path&quot;: &quot;http://localhost:8000/api/v1/admin/tenants&quot;,
        &quot;per_page&quot;: 15,
        &quot;to&quot;: 15,
        &quot;total&quot;: 200
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Missing, invalid, or expired token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;UNAUTHENTICATED&quot;,
        &quot;message&quot;: &quot;Unauthenticated.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, No permission, or wrong user type for this route):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;FORBIDDEN&quot;,
        &quot;message&quot;: &quot;This action is unauthorized.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (422, Validation failed):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;VALIDATION_FAILED&quot;,
        &quot;message&quot;: &quot;The given data was invalid.&quot;,
        &quot;details&quot;: {
            &quot;filter.plan&quot;: [
                &quot;The selected filter.plan is invalid.&quot;
            ]
        }
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (429, Rate limit hit (Retry-After header is set)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;RATE_LIMITED&quot;,
        &quot;message&quot;: &quot;Too many requests. Please slow down.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-admin-tenants" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-admin-tenants"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-admin-tenants"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-admin-tenants" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-admin-tenants">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-admin-tenants" data-method="GET"
      data-path="api/v1/admin/tenants"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-admin-tenants', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-admin-tenants"
                    onclick="tryItOut('GETapi-v1-admin-tenants');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-admin-tenants"
                    onclick="cancelTryOut('GETapi-v1-admin-tenants');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-admin-tenants"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/admin/tenants</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-admin-tenants"
               value="Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-admin-tenants"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-admin-tenants"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                            <h4 class="fancy-heading-panel"><b>Query Parameters</b></h4>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="page"                data-endpoint="GETapi-v1-admin-tenants"
               value="1"
               data-component="query">
    <br>
<p>Page number, from 1. Must be at least 1. Must not be greater than 1000000. Example: <code>1</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>per_page</code></b>&nbsp;&nbsp;
<small>integer</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="number" style="display: none"
               step="any"               name="per_page"                data-endpoint="GETapi-v1-admin-tenants"
               value="15"
               data-component="query">
    <br>
<p>Items per page, 1 to 100. Default 15. Must be at least 1. Must not be greater than 100. Example: <code>15</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>sort</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="sort"                data-endpoint="GETapi-v1-admin-tenants"
               value="name"
               data-component="query">
    <br>
<p>-created_at (default) or name. Example: <code>name</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>-created_at</code></li> <li><code>name</code></li></ul>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>filter</code></b>&nbsp;&nbsp;
<small>object</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="filter"                data-endpoint="GETapi-v1-admin-tenants"
               value=""
               data-component="query">
    <br>

            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>filter.status</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="filter.status"                data-endpoint="GETapi-v1-admin-tenants"
               value="active"
               data-component="query">
    <br>
<p>active or suspended. Example: <code>active</code></p>
Must be one of:
<ul style="list-style-type: square;"><li><code>active</code></li> <li><code>suspended</code></li></ul>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>filter.plan</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="filter.plan"                data-endpoint="GETapi-v1-admin-tenants"
               value="pro"
               data-component="query">
    <br>
<p>A plan code. Must match an existing stored value. Example: <code>pro</code></p>
            </div>
                                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>filter.search</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
<i>optional</i> &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="filter.search"                data-endpoint="GETapi-v1-admin-tenants"
               value="Acme"
               data-component="query">
    <br>
<p>Prefix match on company name. Must not be greater than 100 characters. Example: <code>Acme</code></p>
            </div>
                </form>

                    <h2 id="admin-GETapi-v1-admin-tenants--tenant_ulid-">Show a company</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Platform admin only. Includes the subscription and usage counts.</p>

<span id="example-requests-GETapi-v1-admin-tenants--tenant_ulid-">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/admin/tenants/01m3yqq28g3sa8stzv3t2bjndy" \
    --header "Authorization: Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-admin-tenants--tenant_ulid-">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: &quot;01m3ypws0r7ercmyaecr9y5a12&quot;,
        &quot;name&quot;: &quot;Acme Ltd&quot;,
        &quot;status&quot;: &quot;active&quot;,
        &quot;created_at&quot;: &quot;2026-10-02T16:24:25Z&quot;,
        &quot;subscription&quot;: {
            &quot;id&quot;: &quot;01m3ypws7te03dc3frgev51ras&quot;,
            &quot;status&quot;: &quot;active&quot;,
            &quot;plan&quot;: {
                &quot;id&quot;: &quot;01m3ypwrhqvabc00wv89eb5qez&quot;,
                &quot;code&quot;: &quot;pro&quot;,
                &quot;name&quot;: &quot;Pro&quot;,
                &quot;price_cents&quot;: 4900,
                &quot;currency&quot;: &quot;USD&quot;,
                &quot;features&quot;: [
                    {
                        &quot;key&quot;: &quot;max_users&quot;,
                        &quot;enabled&quot;: true,
                        &quot;limit&quot;: 25
                    },
                    {
                        &quot;key&quot;: &quot;max_customers&quot;,
                        &quot;enabled&quot;: true,
                        &quot;limit&quot;: 5000
                    },
                    {
                        &quot;key&quot;: &quot;api_rate_per_minute&quot;,
                        &quot;enabled&quot;: true,
                        &quot;limit&quot;: 600
                    },
                    {
                        &quot;key&quot;: &quot;customer_export&quot;,
                        &quot;enabled&quot;: true,
                        &quot;limit&quot;: null
                    },
                    {
                        &quot;key&quot;: &quot;analytics_trends&quot;,
                        &quot;enabled&quot;: true,
                        &quot;limit&quot;: null
                    }
                ]
            },
            &quot;trial_ends_at&quot;: null,
            &quot;current_period_start&quot;: &quot;2026-10-02T16:24:25Z&quot;,
            &quot;current_period_end&quot;: &quot;2026-11-02T16:24:25Z&quot;,
            &quot;cancelled_at&quot;: null,
            &quot;ends_at&quot;: null,
            &quot;is_usable&quot;: true
        },
        &quot;usage&quot;: {
            &quot;users&quot;: {
                &quot;used&quot;: 3,
                &quot;limit&quot;: 25,
                &quot;over_limit&quot;: false
            },
            &quot;customers&quot;: {
                &quot;used&quot;: 200,
                &quot;limit&quot;: 5000,
                &quot;over_limit&quot;: false
            }
        }
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Missing, invalid, or expired token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;UNAUTHENTICATED&quot;,
        &quot;message&quot;: &quot;Unauthenticated.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, No permission, or wrong user type for this route):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;FORBIDDEN&quot;,
        &quot;message&quot;: &quot;This action is unauthorized.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not found or belongs to another company):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;NOT_FOUND&quot;,
        &quot;message&quot;: &quot;The requested resource was not found.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (429, Rate limit hit (Retry-After header is set)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;RATE_LIMITED&quot;,
        &quot;message&quot;: &quot;Too many requests. Please slow down.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-admin-tenants--tenant_ulid-" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-admin-tenants--tenant_ulid-"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-admin-tenants--tenant_ulid-"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-admin-tenants--tenant_ulid-" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-admin-tenants--tenant_ulid-">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-admin-tenants--tenant_ulid-" data-method="GET"
      data-path="api/v1/admin/tenants/{tenant_ulid}"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-admin-tenants--tenant_ulid-', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-admin-tenants--tenant_ulid-"
                    onclick="tryItOut('GETapi-v1-admin-tenants--tenant_ulid-');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-admin-tenants--tenant_ulid-"
                    onclick="cancelTryOut('GETapi-v1-admin-tenants--tenant_ulid-');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-admin-tenants--tenant_ulid-"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/admin/tenants/{tenant_ulid}</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-admin-tenants--tenant_ulid-"
               value="Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-admin-tenants--tenant_ulid-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-admin-tenants--tenant_ulid-"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>tenant_ulid</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="tenant_ulid"                data-endpoint="GETapi-v1-admin-tenants--tenant_ulid-"
               value="01m3yqq28g3sa8stzv3t2bjndy"
               data-component="url">
    <br>
<p>Example: <code>01m3yqq28g3sa8stzv3t2bjndy</code></p>
            </div>
                    </form>

                    <h2 id="admin-POSTapi-v1-admin-tenants--tenant_ulid--suspend">Suspend a company</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Platform admin only. Deletes every token of the company users: their next request gets 401 and login gets 403 TENANT_SUSPENDED.</p>

<span id="example-requests-POSTapi-v1-admin-tenants--tenant_ulid--suspend">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/admin/tenants/01m3yqq28g3sa8stzv3t2bjndy/suspend" \
    --header "Authorization: Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-admin-tenants--tenant_ulid--suspend">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: &quot;01m3ypws0r7ercmyaecr9y5a12&quot;,
        &quot;name&quot;: &quot;Acme Ltd&quot;,
        &quot;status&quot;: &quot;suspended&quot;,
        &quot;created_at&quot;: &quot;2026-10-02T16:24:25Z&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Missing, invalid, or expired token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;UNAUTHENTICATED&quot;,
        &quot;message&quot;: &quot;Unauthenticated.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, No permission, or wrong user type for this route):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;FORBIDDEN&quot;,
        &quot;message&quot;: &quot;This action is unauthorized.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not found or belongs to another company):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;NOT_FOUND&quot;,
        &quot;message&quot;: &quot;The requested resource was not found.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (429, Rate limit hit (Retry-After header is set)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;RATE_LIMITED&quot;,
        &quot;message&quot;: &quot;Too many requests. Please slow down.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-admin-tenants--tenant_ulid--suspend" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-admin-tenants--tenant_ulid--suspend"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-admin-tenants--tenant_ulid--suspend"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-admin-tenants--tenant_ulid--suspend" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-admin-tenants--tenant_ulid--suspend">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-admin-tenants--tenant_ulid--suspend" data-method="POST"
      data-path="api/v1/admin/tenants/{tenant_ulid}/suspend"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-admin-tenants--tenant_ulid--suspend', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-admin-tenants--tenant_ulid--suspend"
                    onclick="tryItOut('POSTapi-v1-admin-tenants--tenant_ulid--suspend');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-admin-tenants--tenant_ulid--suspend"
                    onclick="cancelTryOut('POSTapi-v1-admin-tenants--tenant_ulid--suspend');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-admin-tenants--tenant_ulid--suspend"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/admin/tenants/{tenant_ulid}/suspend</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-admin-tenants--tenant_ulid--suspend"
               value="Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-admin-tenants--tenant_ulid--suspend"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-admin-tenants--tenant_ulid--suspend"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>tenant_ulid</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="tenant_ulid"                data-endpoint="POSTapi-v1-admin-tenants--tenant_ulid--suspend"
               value="01m3yqq28g3sa8stzv3t2bjndy"
               data-component="url">
    <br>
<p>Example: <code>01m3yqq28g3sa8stzv3t2bjndy</code></p>
            </div>
                    </form>

                    <h2 id="admin-POSTapi-v1-admin-tenants--tenant_ulid--reactivate">Reactivate a company</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Platform admin only.</p>

<span id="example-requests-POSTapi-v1-admin-tenants--tenant_ulid--reactivate">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request POST \
    "http://localhost:8000/api/v1/admin/tenants/01m3yqq28g3sa8stzv3t2bjndy/reactivate" \
    --header "Authorization: Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>

</span>

<span id="example-responses-POSTapi-v1-admin-tenants--tenant_ulid--reactivate">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;id&quot;: &quot;01m3ypws0r7ercmyaecr9y5a12&quot;,
        &quot;name&quot;: &quot;Acme Ltd&quot;,
        &quot;status&quot;: &quot;active&quot;,
        &quot;created_at&quot;: &quot;2026-10-02T16:24:25Z&quot;
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Missing, invalid, or expired token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;UNAUTHENTICATED&quot;,
        &quot;message&quot;: &quot;Unauthenticated.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, No permission, or wrong user type for this route):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;FORBIDDEN&quot;,
        &quot;message&quot;: &quot;This action is unauthorized.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (404, Not found or belongs to another company):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;NOT_FOUND&quot;,
        &quot;message&quot;: &quot;The requested resource was not found.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (429, Rate limit hit (Retry-After header is set)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;RATE_LIMITED&quot;,
        &quot;message&quot;: &quot;Too many requests. Please slow down.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
    </span>
<span id="execution-results-POSTapi-v1-admin-tenants--tenant_ulid--reactivate" hidden>
    <blockquote>Received response<span
                id="execution-response-status-POSTapi-v1-admin-tenants--tenant_ulid--reactivate"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-POSTapi-v1-admin-tenants--tenant_ulid--reactivate"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-POSTapi-v1-admin-tenants--tenant_ulid--reactivate" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-POSTapi-v1-admin-tenants--tenant_ulid--reactivate">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-POSTapi-v1-admin-tenants--tenant_ulid--reactivate" data-method="POST"
      data-path="api/v1/admin/tenants/{tenant_ulid}/reactivate"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('POSTapi-v1-admin-tenants--tenant_ulid--reactivate', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-POSTapi-v1-admin-tenants--tenant_ulid--reactivate"
                    onclick="tryItOut('POSTapi-v1-admin-tenants--tenant_ulid--reactivate');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-POSTapi-v1-admin-tenants--tenant_ulid--reactivate"
                    onclick="cancelTryOut('POSTapi-v1-admin-tenants--tenant_ulid--reactivate');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-POSTapi-v1-admin-tenants--tenant_ulid--reactivate"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-black">POST</small>
            <b><code>api/v1/admin/tenants/{tenant_ulid}/reactivate</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="POSTapi-v1-admin-tenants--tenant_ulid--reactivate"
               value="Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="POSTapi-v1-admin-tenants--tenant_ulid--reactivate"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="POSTapi-v1-admin-tenants--tenant_ulid--reactivate"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        <h4 class="fancy-heading-panel"><b>URL Parameters</b></h4>
                    <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>tenant_ulid</code></b>&nbsp;&nbsp;
<small>string</small>&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="tenant_ulid"                data-endpoint="POSTapi-v1-admin-tenants--tenant_ulid--reactivate"
               value="01m3yqq28g3sa8stzv3t2bjndy"
               data-component="url">
    <br>
<p>Example: <code>01m3yqq28g3sa8stzv3t2bjndy</code></p>
            </div>
                    </form>

                    <h2 id="admin-GETapi-v1-admin-dashboard">Platform dashboard</h2>

<p>
<small class="badge badge-darkred">requires authentication</small>
</p>

<p>Platform admin only. MRR counts active and past_due subscriptions on paid plans. Churn counts cancelled and expired events in the last 30 days. Cached for 5 minutes.</p>

<span id="example-requests-GETapi-v1-admin-dashboard">
<blockquote>Example request:</blockquote>


<div class="bash-example">
    <pre><code class="language-bash">curl --request GET \
    --get "http://localhost:8000/api/v1/admin/dashboard" \
    --header "Authorization: Bearer {YOUR_TOKEN}" \
    --header "Content-Type: application/json" \
    --header "Accept: application/json"</code></pre></div>

</span>

<span id="example-responses-GETapi-v1-admin-dashboard">
            <blockquote>
            <p>Example response (200):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;data&quot;: {
        &quot;tenants&quot;: {
            &quot;total&quot;: 3,
            &quot;active&quot;: 3,
            &quot;suspended&quot;: 0,
            &quot;new_last_30_days&quot;: 3
        },
        &quot;subscriptions_by_status&quot;: {
            &quot;trialing&quot;: 1,
            &quot;active&quot;: 2,
            &quot;past_due&quot;: 0,
            &quot;cancelled&quot;: 0,
            &quot;expired&quot;: 0
        },
        &quot;subscriptions_by_plan&quot;: {
            &quot;free&quot;: 1,
            &quot;starter&quot;: 1,
            &quot;pro&quot;: 1
        },
        &quot;mrr_cents&quot;: 4900,
        &quot;churned_last_30_days&quot;: 0
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (401, Missing, invalid, or expired token):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;UNAUTHENTICATED&quot;,
        &quot;message&quot;: &quot;Unauthenticated.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (403, No permission, or wrong user type for this route):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;FORBIDDEN&quot;,
        &quot;message&quot;: &quot;This action is unauthorized.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
            <blockquote>
            <p>Example response (429, Rate limit hit (Retry-After header is set)):</p>
        </blockquote>
                <pre>

<code class="language-json" style="max-height: 300px;">{
    &quot;error&quot;: {
        &quot;code&quot;: &quot;RATE_LIMITED&quot;,
        &quot;message&quot;: &quot;Too many requests. Please slow down.&quot;,
        &quot;details&quot;: {}
    }
}</code>
 </pre>
    </span>
<span id="execution-results-GETapi-v1-admin-dashboard" hidden>
    <blockquote>Received response<span
                id="execution-response-status-GETapi-v1-admin-dashboard"></span>:
    </blockquote>
    <pre class="json"><code id="execution-response-content-GETapi-v1-admin-dashboard"
      data-empty-response-text="<Empty response>" style="max-height: 400px;"></code></pre>
</span>
<span id="execution-error-GETapi-v1-admin-dashboard" hidden>
    <blockquote>Request failed with error:</blockquote>
    <pre><code id="execution-error-message-GETapi-v1-admin-dashboard">

Tip: Check that you&#039;re properly connected to the network.
If you&#039;re a maintainer of ths API, verify that your API is running and you&#039;ve enabled CORS.
You can check the Dev Tools console for debugging information.</code></pre>
</span>
<form id="form-GETapi-v1-admin-dashboard" data-method="GET"
      data-path="api/v1/admin/dashboard"
      data-authed="1"
      data-hasfiles="0"
      data-isarraybody="0"
      autocomplete="off"
      onsubmit="event.preventDefault(); executeTryOut('GETapi-v1-admin-dashboard', this);">
    <h3>
        Request&nbsp;&nbsp;&nbsp;
                    <button type="button"
                    style="background-color: #8fbcd4; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-tryout-GETapi-v1-admin-dashboard"
                    onclick="tryItOut('GETapi-v1-admin-dashboard');">Try it out ⚡
            </button>
            <button type="button"
                    style="background-color: #c97a7e; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-canceltryout-GETapi-v1-admin-dashboard"
                    onclick="cancelTryOut('GETapi-v1-admin-dashboard');" hidden>Cancel 🛑
            </button>&nbsp;&nbsp;
            <button type="submit"
                    style="background-color: #6ac174; padding: 5px 10px; border-radius: 5px; border-width: thin;"
                    id="btn-executetryout-GETapi-v1-admin-dashboard"
                    data-initial-text="Send Request 💥"
                    data-loading-text="⏱ Sending..."
                    hidden>Send Request 💥
            </button>
            </h3>
            <p>
            <small class="badge badge-green">GET</small>
            <b><code>api/v1/admin/dashboard</code></b>
        </p>
                <h4 class="fancy-heading-panel"><b>Headers</b></h4>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Authorization</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Authorization" class="auth-value"               data-endpoint="GETapi-v1-admin-dashboard"
               value="Bearer {YOUR_TOKEN}"
               data-component="header">
    <br>
<p>Example: <code>Bearer {YOUR_TOKEN}</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Content-Type</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Content-Type"                data-endpoint="GETapi-v1-admin-dashboard"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                                <div style="padding-left: 28px; clear: unset;">
                <b style="line-height: 2;"><code>Accept</code></b>&nbsp;&nbsp;
&nbsp;
 &nbsp;
 &nbsp;
                <input type="text" style="display: none"
                              name="Accept"                data-endpoint="GETapi-v1-admin-dashboard"
               value="application/json"
               data-component="header">
    <br>
<p>Example: <code>application/json</code></p>
            </div>
                        </form>

            

        
    </div>
    <div class="dark-box">
                    <div class="lang-selector">
                                                        <button type="button" class="lang-button" data-language-name="bash">bash</button>
                            </div>
            </div>
</div>
</body>
</html>
