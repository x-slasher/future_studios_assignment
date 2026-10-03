<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\PlanService;
use Illuminate\Http\JsonResponse;

/**
 * @group Plans
 *
 * Public plan list.
 */
final class PlanController extends Controller
{
    public function __construct(private readonly PlanService $plans) {}

    /**
     * List plans
     *
     * Active plans with their features, in display order. Cached. Limited to 60 requests per minute per IP.
     *
     * @unauthenticated
     *
     * @response 200 {"data": [{"id": "01m3ypwrg7s3rw8thb4tw1226n", "code": "starter", "name": "Starter", "price_cents": 1900, "currency": "USD", "features": [{"key": "max_users", "enabled": true, "limit": 5}, {"key": "max_customers", "enabled": true, "limit": 500}, {"key": "api_rate_per_minute", "enabled": true, "limit": 120}, {"key": "customer_export", "enabled": true, "limit": null}, {"key": "analytics_trends", "enabled": false, "limit": null}]}, {"id": "01m3ypwrhqvabc00wv89eb5qez", "code": "pro", "name": "Pro", "price_cents": 4900, "currency": "USD", "features": [{"key": "max_users", "enabled": true, "limit": 25}, {"key": "max_customers", "enabled": true, "limit": 5000}, {"key": "api_rate_per_minute", "enabled": true, "limit": 600}, {"key": "customer_export", "enabled": true, "limit": null}, {"key": "analytics_trends", "enabled": true, "limit": null}]}]}
     * @response 429 scenario="Rate limit hit (Retry-After header is set)" {"error": {"code": "RATE_LIMITED", "message": "Too many requests. Please slow down.", "details": {}}}
     */
    public function index(): JsonResponse
    {
        return new JsonResponse(['data' => $this->plans->activeForDisplay()]);
    }
}
