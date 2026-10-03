<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ListTenantsRequest;
use App\Http\Resources\TenantResource;
use App\Models\Tenant;
use App\Services\TenantService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @group Admin
 *
 * Platform admin endpoints.
 *
 * Access is enforced by the platform.admin middleware on the admin route group.
 */
final class TenantController extends Controller
{
    public function __construct(private readonly TenantService $tenants) {}

    /**
     * List companies
     *
     * Platform admin only. Each company comes with its subscription and plan.
     *
     * @authenticated
     *
     * @response 200 {"data": [{"id": "01m3ypws0r7ercmyaecr9y5a12", "name": "Acme Ltd", "status": "active", "created_at": "2026-10-02T16:24:25Z", "subscription": {"id": "01m3ypws7te03dc3frgev51ras", "status": "active", "plan": {"id": "01m3ypwrhqvabc00wv89eb5qez", "code": "pro", "name": "Pro", "price_cents": 4900, "currency": "USD", "features": [{"key": "max_users", "enabled": true, "limit": 25}, {"key": "max_customers", "enabled": true, "limit": 5000}, {"key": "api_rate_per_minute", "enabled": true, "limit": 600}, {"key": "customer_export", "enabled": true, "limit": null}, {"key": "analytics_trends", "enabled": true, "limit": null}]}, "trial_ends_at": null, "current_period_start": "2026-10-02T16:24:25Z", "current_period_end": "2026-11-02T16:24:25Z", "cancelled_at": null, "ends_at": null, "is_usable": true}}], "links": {"first": "http://localhost:8000/api/v1/admin/tenants?page=1", "last": "http://localhost:8000/api/v1/admin/tenants?page=14", "prev": null, "next": "http://localhost:8000/api/v1/admin/tenants?page=2"}, "meta": {"current_page": 1, "from": 1, "last_page": 14, "path": "http://localhost:8000/api/v1/admin/tenants", "per_page": 15, "to": 15, "total": 200}}
     * @response 401 scenario="Missing, invalid, or expired token" {"error": {"code": "UNAUTHENTICATED", "message": "Unauthenticated.", "details": {}}}
     * @response 403 scenario="No permission, or wrong user type for this route" {"error": {"code": "FORBIDDEN", "message": "This action is unauthorized.", "details": {}}}
     * @response 429 scenario="Rate limit hit (Retry-After header is set)" {"error": {"code": "RATE_LIMITED", "message": "Too many requests. Please slow down.", "details": {}}}
     * @response 422 scenario="Validation failed" {"error": {"code": "VALIDATION_FAILED", "message": "The given data was invalid.", "details": {"filter.plan": ["The selected filter.plan is invalid."]}}}
     */
    public function index(ListTenantsRequest $request): AnonymousResourceCollection
    {
        return TenantResource::collection($this->tenants->adminList($request->toFilter()));
    }

    /**
     * Show a company
     *
     * Platform admin only. Includes the subscription and usage counts.
     *
     * @authenticated
     *
     * @response 200 {"data": {"id": "01m3ypws0r7ercmyaecr9y5a12", "name": "Acme Ltd", "status": "active", "created_at": "2026-10-02T16:24:25Z", "subscription": {"id": "01m3ypws7te03dc3frgev51ras", "status": "active", "plan": {"id": "01m3ypwrhqvabc00wv89eb5qez", "code": "pro", "name": "Pro", "price_cents": 4900, "currency": "USD", "features": [{"key": "max_users", "enabled": true, "limit": 25}, {"key": "max_customers", "enabled": true, "limit": 5000}, {"key": "api_rate_per_minute", "enabled": true, "limit": 600}, {"key": "customer_export", "enabled": true, "limit": null}, {"key": "analytics_trends", "enabled": true, "limit": null}]}, "trial_ends_at": null, "current_period_start": "2026-10-02T16:24:25Z", "current_period_end": "2026-11-02T16:24:25Z", "cancelled_at": null, "ends_at": null, "is_usable": true}, "usage": {"users": {"used": 3, "limit": 25, "over_limit": false}, "customers": {"used": 200, "limit": 5000, "over_limit": false}}}}
     * @response 401 scenario="Missing, invalid, or expired token" {"error": {"code": "UNAUTHENTICATED", "message": "Unauthenticated.", "details": {}}}
     * @response 403 scenario="No permission, or wrong user type for this route" {"error": {"code": "FORBIDDEN", "message": "This action is unauthorized.", "details": {}}}
     * @response 429 scenario="Rate limit hit (Retry-After header is set)" {"error": {"code": "RATE_LIMITED", "message": "Too many requests. Please slow down.", "details": {}}}
     * @response 404 scenario="Not found or belongs to another company" {"error": {"code": "NOT_FOUND", "message": "The requested resource was not found.", "details": {}}}
     */
    public function show(Tenant $tenant): TenantResource
    {
        $detail = $this->tenants->adminShow($tenant);

        return TenantResource::make($detail['tenant'])->withUsage($detail['usage']);
    }

    /**
     * Suspend a company
     *
     * Platform admin only. Deletes every token of the company users: their next request gets 401 and login gets 403 TENANT_SUSPENDED.
     *
     * @authenticated
     *
     * @response 200 {"data": {"id": "01m3ypws0r7ercmyaecr9y5a12", "name": "Acme Ltd", "status": "suspended", "created_at": "2026-10-02T16:24:25Z"}}
     * @response 401 scenario="Missing, invalid, or expired token" {"error": {"code": "UNAUTHENTICATED", "message": "Unauthenticated.", "details": {}}}
     * @response 403 scenario="No permission, or wrong user type for this route" {"error": {"code": "FORBIDDEN", "message": "This action is unauthorized.", "details": {}}}
     * @response 429 scenario="Rate limit hit (Retry-After header is set)" {"error": {"code": "RATE_LIMITED", "message": "Too many requests. Please slow down.", "details": {}}}
     * @response 404 scenario="Not found or belongs to another company" {"error": {"code": "NOT_FOUND", "message": "The requested resource was not found.", "details": {}}}
     */
    public function suspend(Tenant $tenant): TenantResource
    {
        return TenantResource::make($this->tenants->suspend($tenant));
    }

    /**
     * Reactivate a company
     *
     * Platform admin only.
     *
     * @authenticated
     *
     * @response 200 {"data": {"id": "01m3ypws0r7ercmyaecr9y5a12", "name": "Acme Ltd", "status": "active", "created_at": "2026-10-02T16:24:25Z"}}
     * @response 401 scenario="Missing, invalid, or expired token" {"error": {"code": "UNAUTHENTICATED", "message": "Unauthenticated.", "details": {}}}
     * @response 403 scenario="No permission, or wrong user type for this route" {"error": {"code": "FORBIDDEN", "message": "This action is unauthorized.", "details": {}}}
     * @response 429 scenario="Rate limit hit (Retry-After header is set)" {"error": {"code": "RATE_LIMITED", "message": "Too many requests. Please slow down.", "details": {}}}
     * @response 404 scenario="Not found or belongs to another company" {"error": {"code": "NOT_FOUND", "message": "The requested resource was not found.", "details": {}}}
     */
    public function reactivate(Tenant $tenant): TenantResource
    {
        return TenantResource::make($this->tenants->reactivate($tenant));
    }
}
