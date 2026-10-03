<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePlanRequest;
use App\Http\Requests\Admin\UpdatePlanRequest;
use App\Http\Resources\PlanResource;
use App\Models\Plan;
use App\Services\PlanService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @group Admin
 *
 * Platform admin endpoints.
 *
 * Access is enforced by the platform.admin middleware on the admin route group.
 */
final class PlanController extends Controller
{
    public function __construct(private readonly PlanService $plans) {}

    /**
     * List all plans
     *
     * Platform admin only. Includes inactive plans.
     *
     * @authenticated
     *
     * @response 200 {"data": [{"id": "01m3ypwrg7s3rw8thb4tw1226n", "code": "starter", "name": "Starter", "price_cents": 1900, "currency": "USD", "features": [{"key": "max_users", "enabled": true, "limit": 5}, {"key": "max_customers", "enabled": true, "limit": 500}, {"key": "api_rate_per_minute", "enabled": true, "limit": 120}, {"key": "customer_export", "enabled": true, "limit": null}, {"key": "analytics_trends", "enabled": false, "limit": null}]}, {"id": "01m3ypwrhqvabc00wv89eb5qez", "code": "pro", "name": "Pro", "price_cents": 4900, "currency": "USD", "features": [{"key": "max_users", "enabled": true, "limit": 25}, {"key": "max_customers", "enabled": true, "limit": 5000}, {"key": "api_rate_per_minute", "enabled": true, "limit": 600}, {"key": "customer_export", "enabled": true, "limit": null}, {"key": "analytics_trends", "enabled": true, "limit": null}]}]}
     * @response 401 scenario="Missing, invalid, or expired token" {"error": {"code": "UNAUTHENTICATED", "message": "Unauthenticated.", "details": {}}}
     * @response 403 scenario="No permission, or wrong user type for this route" {"error": {"code": "FORBIDDEN", "message": "This action is unauthorized.", "details": {}}}
     * @response 429 scenario="Rate limit hit (Retry-After header is set)" {"error": {"code": "RATE_LIMITED", "message": "Too many requests. Please slow down.", "details": {}}}
     */
    public function index(): AnonymousResourceCollection
    {
        return PlanResource::collection($this->plans->all());
    }

    /**
     * Create a plan
     *
     * Platform admin only.
     *
     * @authenticated
     *
     * @response 201 {"data": {"id": "01m3ypwrhqvabc00wv89eb5qez", "code": "business", "name": "Business", "price_cents": 4900, "currency": "USD", "features": [{"key": "max_users", "enabled": true, "limit": 25}, {"key": "max_customers", "enabled": true, "limit": 5000}, {"key": "api_rate_per_minute", "enabled": true, "limit": 600}, {"key": "customer_export", "enabled": true, "limit": null}, {"key": "analytics_trends", "enabled": true, "limit": null}]}}
     * @response 401 scenario="Missing, invalid, or expired token" {"error": {"code": "UNAUTHENTICATED", "message": "Unauthenticated.", "details": {}}}
     * @response 403 scenario="No permission, or wrong user type for this route" {"error": {"code": "FORBIDDEN", "message": "This action is unauthorized.", "details": {}}}
     * @response 429 scenario="Rate limit hit (Retry-After header is set)" {"error": {"code": "RATE_LIMITED", "message": "Too many requests. Please slow down.", "details": {}}}
     * @response 422 scenario="Validation failed" {"error": {"code": "VALIDATION_FAILED", "message": "The given data was invalid.", "details": {"code": ["The code has already been taken."]}}}
     */
    public function store(StorePlanRequest $request): JsonResponse
    {
        return PlanResource::make($this->plans->create($request->toDto()))->response()->setStatusCode(201);
    }

    /**
     * Update a plan
     *
     * Platform admin only. All fields optional; code cannot change. features, when sent, replaces all feature rows. Every company on the plan sees new limits on its next request.
     *
     * @authenticated
     *
     * @response 200 {"data": {"id": "01m3ypwrhqvabc00wv89eb5qez", "code": "pro", "name": "Pro", "price_cents": 4900, "currency": "USD", "features": [{"key": "max_users", "enabled": true, "limit": 25}, {"key": "max_customers", "enabled": true, "limit": 5000}, {"key": "api_rate_per_minute", "enabled": true, "limit": 600}, {"key": "customer_export", "enabled": true, "limit": null}, {"key": "analytics_trends", "enabled": true, "limit": null}]}}
     * @response 401 scenario="Missing, invalid, or expired token" {"error": {"code": "UNAUTHENTICATED", "message": "Unauthenticated.", "details": {}}}
     * @response 403 scenario="No permission, or wrong user type for this route" {"error": {"code": "FORBIDDEN", "message": "This action is unauthorized.", "details": {}}}
     * @response 429 scenario="Rate limit hit (Retry-After header is set)" {"error": {"code": "RATE_LIMITED", "message": "Too many requests. Please slow down.", "details": {}}}
     * @response 404 scenario="Not found or belongs to another company" {"error": {"code": "NOT_FOUND", "message": "The requested resource was not found.", "details": {}}}
     * @response 422 scenario="Validation failed" {"error": {"code": "VALIDATION_FAILED", "message": "The given data was invalid.", "details": {"code": ["The code field is prohibited."]}}}
     */
    public function update(UpdatePlanRequest $request, Plan $plan): PlanResource
    {
        return PlanResource::make($this->plans->update($plan, $request->toDto()));
    }
}
