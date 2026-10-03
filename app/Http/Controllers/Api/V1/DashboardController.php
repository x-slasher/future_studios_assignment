<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Enums\Permission;
use App\Http\Controllers\Controller;
use App\Services\TenantDashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

/**
 * @group Dashboard
 *
 * Usage and analytics.
 */
final class DashboardController extends Controller
{
    public function __construct(private readonly TenantDashboardService $dashboard) {}

    /**
     * Company dashboard
     *
     * Subscription, usage against limits, customer counts, and a 30-day trend. trend is null when the plan lacks analytics_trends. Cached for 10 minutes and refreshed on any company data change.
     *
     * @authenticated
     *
     * @response 200 {"data": {"subscription": {"status": "active", "plan": "pro", "current_period_end": "2026-11-02T16:24:25Z", "days_left": 31}, "usage": {"users": {"used": 3, "limit": 25, "over_limit": false}, "customers": {"used": 200, "limit": 5000, "over_limit": false}}, "customers": {"total": 200, "active": 180, "inactive": 20, "new_last_30_days": 69}, "trend": [{"date": "2026-09-03", "users": 3, "customers": 156}, {"date": "2026-09-04", "users": 3, "customers": 158}]}}
     * @response 401 scenario="Missing, invalid, or expired token" {"error": {"code": "UNAUTHENTICATED", "message": "Unauthenticated.", "details": {}}}
     * @response 403 scenario="No permission, or wrong user type for this route" {"error": {"code": "FORBIDDEN", "message": "This action is unauthorized.", "details": {}}}
     * @response 403 scenario="Company suspended" {"error": {"code": "TENANT_SUSPENDED", "message": "Your company account is suspended.", "details": {}}}
     * @response 429 scenario="Rate limit hit (Retry-After header is set)" {"error": {"code": "RATE_LIMITED", "message": "Too many requests. Please slow down.", "details": {}}}
     */
    public function show(): JsonResponse
    {
        Gate::authorize(Permission::DashboardView->value);

        return new JsonResponse(['data' => $this->dashboard->summary()]);
    }
}
