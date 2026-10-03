<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Services\PlatformDashboardService;
use Illuminate\Http\JsonResponse;

/**
 * @group Admin
 *
 * Platform admin endpoints.
 *
 * Access is enforced by the platform.admin middleware on the admin route group.
 */
final class DashboardController extends Controller
{
    public function __construct(private readonly PlatformDashboardService $dashboard) {}

    /**
     * Platform dashboard
     *
     * Platform admin only. MRR counts active and past_due subscriptions on paid plans. Churn counts cancelled and expired events in the last 30 days. Cached for 5 minutes.
     *
     * @authenticated
     *
     * @response 200 {"data": {"tenants": {"total": 3, "active": 3, "suspended": 0, "new_last_30_days": 3}, "subscriptions_by_status": {"trialing": 1, "active": 2, "past_due": 0, "cancelled": 0, "expired": 0}, "subscriptions_by_plan": {"free": 1, "starter": 1, "pro": 1}, "mrr_cents": 4900, "churned_last_30_days": 0}}
     * @response 401 scenario="Missing, invalid, or expired token" {"error": {"code": "UNAUTHENTICATED", "message": "Unauthenticated.", "details": {}}}
     * @response 403 scenario="No permission, or wrong user type for this route" {"error": {"code": "FORBIDDEN", "message": "This action is unauthorized.", "details": {}}}
     * @response 429 scenario="Rate limit hit (Retry-After header is set)" {"error": {"code": "RATE_LIMITED", "message": "Too many requests. Please slow down.", "details": {}}}
     */
    public function show(): JsonResponse
    {
        return new JsonResponse(['data' => $this->dashboard->summary()]);
    }
}
