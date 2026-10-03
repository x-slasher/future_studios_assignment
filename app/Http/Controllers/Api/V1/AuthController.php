<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\SubscriptionResource;
use App\Http\Resources\TenantResource;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use App\Services\TenantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @group Auth
 *
 * Registration, login, and passwords.
 */
final class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $auth,
        private readonly TenantService $tenants,
    ) {}

    /**
     * Register a company
     *
     * Creates the company, its owner, and a subscription in one transaction, then returns a token so the client is logged in at once. A paid plan starts a 14-day trial; free starts active with no period. The owner gets a welcome email.
     *
     * @unauthenticated
     *
     * @response 201 {"data": {"token": "1|example-token", "user": {"id": "01m3yq2m67tyfq22zt14a0ymyq", "name": "Rahim Uddin", "email": "rahim@rahimtraders.test", "role": "owner", "is_active": true, "last_login_at": null, "created_at": "2026-10-02T16:27:37Z", "tenant": {"id": "01m3yq2kzkqwbq7wbddstwev0v", "name": "Rahim Traders", "status": "active", "created_at": "2026-10-02T16:27:36Z"}}, "tenant": {"id": "01m3yq2kzkqwbq7wbddstwev0v", "name": "Rahim Traders", "status": "active", "created_at": "2026-10-02T16:27:36Z"}, "subscription": {"id": "01m3yq2m6ch3k7tjxzbr2528rx", "status": "trialing", "plan": {"id": "01m3ypwrg7s3rw8thb4tw1226n", "code": "starter", "name": "Starter", "price_cents": 1900, "currency": "USD", "features": [{"key": "max_users", "enabled": true, "limit": 5}, {"key": "max_customers", "enabled": true, "limit": 500}, {"key": "api_rate_per_minute", "enabled": true, "limit": 120}, {"key": "customer_export", "enabled": true, "limit": null}, {"key": "analytics_trends", "enabled": false, "limit": null}]}, "trial_ends_at": "2026-10-16T16:27:37Z", "current_period_start": null, "current_period_end": null, "cancelled_at": null, "ends_at": null, "is_usable": true}}}
     * @response 422 scenario="Validation failed" {"error": {"code": "VALIDATION_FAILED", "message": "The given data was invalid.", "details": {"email": ["The email has already been taken."], "plan_code": ["The selected plan code is invalid."]}}}
     * @response 429 scenario="Rate limit hit (Retry-After header is set)" {"error": {"code": "RATE_LIMITED", "message": "Too many requests. Please slow down.", "details": {}}}
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $result = $this->tenants->register($request->toDto());

        return new JsonResponse(['data' => [
            'token' => $result->token,
            'user' => UserResource::make($result->user),
            'tenant' => TenantResource::make($result->tenant),
            'subscription' => SubscriptionResource::make($result->subscription),
        ]], 201);
    }

    /**
     * Log in
     *
     * Returns a token named "api" that expires after 7 days. Unknown email, wrong password, and inactive user all return the same 401.
     *
     * @unauthenticated
     *
     * @response 200 {"data": {"token": "1|example-token", "user": {"id": "01m3ypws7j18e4984mfb5kkz73", "name": "Acme Ltd Owner", "email": "owner@acme.test", "role": "owner", "is_active": true, "last_login_at": "2026-10-02T16:27:37Z", "created_at": "2026-10-02T16:24:25Z", "tenant": {"id": "01m3ypws0r7ercmyaecr9y5a12", "name": "Acme Ltd", "status": "active", "created_at": "2026-10-02T16:24:25Z"}}}}
     * @response 401 scenario="Wrong email or password, or inactive user" {"error": {"code": "INVALID_CREDENTIALS", "message": "These credentials do not match our records.", "details": {}}}
     * @response 403 scenario="Company suspended" {"error": {"code": "TENANT_SUSPENDED", "message": "Your company account is suspended.", "details": {}}}
     * @response 422 scenario="Validation failed" {"error": {"code": "VALIDATION_FAILED", "message": "The given data was invalid.", "details": {"email": ["The email field is required."]}}}
     * @response 429 scenario="Rate limit hit (Retry-After header is set)" {"error": {"code": "RATE_LIMITED", "message": "Too many requests. Please slow down.", "details": {}}}
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->auth->login($request->toDto());

        return new JsonResponse(['data' => [
            'token' => $result['token'],
            'user' => UserResource::make($result['user']),
        ]]);
    }

    /**
     * Log out
     *
     * Deletes only the token used for this request. Other devices stay logged in.
     *
     * @authenticated
     *
     * @response 204 scenario="Logged out" ""
     * @response 401 scenario="Missing, invalid, or expired token" {"error": {"code": "UNAUTHENTICATED", "message": "Unauthenticated.", "details": {}}}
     * @response 429 scenario="Rate limit hit (Retry-After header is set)" {"error": {"code": "RATE_LIMITED", "message": "Too many requests. Please slow down.", "details": {}}}
     */
    public function logout(Request $request): Response
    {
        $this->auth->logout($request->user());

        return response()->noContent();
    }

    /**
     * Current user
     *
     * Works for tenant users and platform admins. Returns the role, a company summary, and the permission list. A platform admin has no role, company, or permissions.
     *
     * @authenticated
     *
     * @response 200 {"data": {"id": "01m3ypws7j18e4984mfb5kkz73", "name": "Acme Ltd Owner", "email": "owner@acme.test", "role": "owner", "is_active": true, "last_login_at": "2026-10-02T16:27:37Z", "created_at": "2026-10-02T16:24:25Z", "tenant": {"id": "01m3ypws0r7ercmyaecr9y5a12", "name": "Acme Ltd", "status": "active", "created_at": "2026-10-02T16:24:25Z"}, "permissions": ["tenant.view", "tenant.update", "users.view", "users.create", "users.update", "users.delete", "customers.view", "customers.create", "customers.update", "customers.delete", "customers.export", "subscription.view", "subscription.manage", "dashboard.view"]}}
     * @response 401 scenario="Missing, invalid, or expired token" {"error": {"code": "UNAUTHENTICATED", "message": "Unauthenticated.", "details": {}}}
     * @response 429 scenario="Rate limit hit (Retry-After header is set)" {"error": {"code": "RATE_LIMITED", "message": "Too many requests. Please slow down.", "details": {}}}
     */
    public function me(Request $request): UserResource
    {
        $profile = $this->auth->me($request->user());

        return UserResource::make($profile['user'])->withPermissions($profile['permissions']);
    }
}
