<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Billing\ChangePlanRequest;
use App\Http\Requests\Billing\RenewSubscriptionRequest;
use App\Http\Resources\SubscriptionResource;
use App\Models\Subscription;
use App\Services\SubscriptionService;
use Illuminate\Support\Facades\Gate;

/**
 * @group Subscription
 *
 * Your company subscription. There is no payment gateway; renew simulates a successful payment.
 */
final class SubscriptionController extends Controller
{
    public function __construct(private readonly SubscriptionService $subscriptions) {}

    /**
     * Show the subscription
     *
     * Plan, status, dates, features, and usage against the plan limits. Needs subscription.view.
     *
     * @authenticated
     *
     * @response 200 {"data": {"id": "01m3ypws7te03dc3frgev51ras", "status": "active", "plan": {"id": "01m3ypwrhqvabc00wv89eb5qez", "code": "pro", "name": "Pro", "price_cents": 4900, "currency": "USD", "features": [{"key": "max_users", "enabled": true, "limit": 25}, {"key": "max_customers", "enabled": true, "limit": 5000}, {"key": "api_rate_per_minute", "enabled": true, "limit": 600}, {"key": "customer_export", "enabled": true, "limit": null}, {"key": "analytics_trends", "enabled": true, "limit": null}]}, "trial_ends_at": null, "current_period_start": "2026-10-02T16:24:25Z", "current_period_end": "2026-11-02T16:24:25Z", "cancelled_at": null, "ends_at": null, "is_usable": true, "usage": {"users": {"used": 3, "limit": 25, "over_limit": false}, "customers": {"used": 200, "limit": 5000, "over_limit": false}}}}
     * @response 401 scenario="Missing, invalid, or expired token" {"error": {"code": "UNAUTHENTICATED", "message": "Unauthenticated.", "details": {}}}
     * @response 403 scenario="No permission, or wrong user type for this route" {"error": {"code": "FORBIDDEN", "message": "This action is unauthorized.", "details": {}}}
     * @response 403 scenario="Company suspended" {"error": {"code": "TENANT_SUSPENDED", "message": "Your company account is suspended.", "details": {}}}
     * @response 429 scenario="Rate limit hit (Retry-After header is set)" {"error": {"code": "RATE_LIMITED", "message": "Too many requests. Please slow down.", "details": {}}}
     */
    public function show(): SubscriptionResource
    {
        Gate::authorize('view', Subscription::class);

        return $this->toResource($this->subscriptions->overview());
    }

    /**
     * Change plan
     *
     * Switches between plans and keeps the status and dates. Moving to free makes the subscription active with no period. A free subscription must use renew to start a paid plan. Downgrading over a limit is allowed; new additions are blocked. Owner only.
     *
     * @authenticated
     *
     * @response 200 {"data": {"id": "01m3ypws7te03dc3frgev51ras", "status": "active", "plan": {"id": "01m3ypwrhqvabc00wv89eb5qez", "code": "pro", "name": "Pro", "price_cents": 4900, "currency": "USD", "features": [{"key": "max_users", "enabled": true, "limit": 25}, {"key": "max_customers", "enabled": true, "limit": 5000}, {"key": "api_rate_per_minute", "enabled": true, "limit": 600}, {"key": "customer_export", "enabled": true, "limit": null}, {"key": "analytics_trends", "enabled": true, "limit": null}]}, "trial_ends_at": null, "current_period_start": "2026-10-02T16:24:25Z", "current_period_end": "2026-11-02T16:24:25Z", "cancelled_at": null, "ends_at": null, "is_usable": true, "usage": {"users": {"used": 3, "limit": 25, "over_limit": false}, "customers": {"used": 200, "limit": 5000, "over_limit": false}}}}
     * @response 401 scenario="Missing, invalid, or expired token" {"error": {"code": "UNAUTHENTICATED", "message": "Unauthenticated.", "details": {}}}
     * @response 403 scenario="No permission, or wrong user type for this route" {"error": {"code": "FORBIDDEN", "message": "This action is unauthorized.", "details": {}}}
     * @response 403 scenario="Company suspended" {"error": {"code": "TENANT_SUSPENDED", "message": "Your company account is suspended.", "details": {}}}
     * @response 429 scenario="Rate limit hit (Retry-After header is set)" {"error": {"code": "RATE_LIMITED", "message": "Too many requests. Please slow down.", "details": {}}}
     * @response 409 scenario="Change not allowed from the current state" {"error": {"code": "INVALID_SUBSCRIPTION_TRANSITION", "message": "Use renew to start a paid plan.", "details": {}}}
     * @response 422 scenario="Validation failed" {"error": {"code": "VALIDATION_FAILED", "message": "The given data was invalid.", "details": {"plan_code": ["The selected plan code is invalid."]}}}
     */
    public function changePlan(ChangePlanRequest $request): SubscriptionResource
    {
        Gate::authorize('manage', Subscription::class);

        return $this->toResource($this->subscriptions->changePlan($request->planCode()));
    }

    /**
     * Renew (simulated payment)
     *
     * Payment stub. Sets the status to active with a one-month period from now, optionally on another paid plan. Works from trialing, past_due, cancelled, expired, or free. Owner only.
     *
     * @authenticated
     *
     * @response 200 {"data": {"id": "01m3ypws7te03dc3frgev51ras", "status": "active", "plan": {"id": "01m3ypwrhqvabc00wv89eb5qez", "code": "pro", "name": "Pro", "price_cents": 4900, "currency": "USD", "features": [{"key": "max_users", "enabled": true, "limit": 25}, {"key": "max_customers", "enabled": true, "limit": 5000}, {"key": "api_rate_per_minute", "enabled": true, "limit": 600}, {"key": "customer_export", "enabled": true, "limit": null}, {"key": "analytics_trends", "enabled": true, "limit": null}]}, "trial_ends_at": null, "current_period_start": "2026-10-02T16:24:25Z", "current_period_end": "2026-11-02T16:24:25Z", "cancelled_at": null, "ends_at": null, "is_usable": true, "usage": {"users": {"used": 3, "limit": 25, "over_limit": false}, "customers": {"used": 200, "limit": 5000, "over_limit": false}}}}
     * @response 401 scenario="Missing, invalid, or expired token" {"error": {"code": "UNAUTHENTICATED", "message": "Unauthenticated.", "details": {}}}
     * @response 403 scenario="No permission, or wrong user type for this route" {"error": {"code": "FORBIDDEN", "message": "This action is unauthorized.", "details": {}}}
     * @response 403 scenario="Company suspended" {"error": {"code": "TENANT_SUSPENDED", "message": "Your company account is suspended.", "details": {}}}
     * @response 429 scenario="Rate limit hit (Retry-After header is set)" {"error": {"code": "RATE_LIMITED", "message": "Too many requests. Please slow down.", "details": {}}}
     * @response 409 scenario="Change not allowed from the current state" {"error": {"code": "INVALID_SUBSCRIPTION_TRANSITION", "message": "The subscription is already active.", "details": {}}}
     * @response 422 scenario="Validation failed" {"error": {"code": "VALIDATION_FAILED", "message": "The given data was invalid.", "details": {"plan_code": ["The selected plan code is invalid."]}}}
     */
    public function renew(RenewSubscriptionRequest $request): SubscriptionResource
    {
        Gate::authorize('manage', Subscription::class);

        return $this->toResource($this->subscriptions->renew($request->planCode()));
    }

    /**
     * Cancel
     *
     * Access continues until the trial or period end (immediately when past_due). A free plan cannot be cancelled. Owner only.
     *
     * @authenticated
     *
     * @response 200 {"data": {"id": "01m3ypws7te03dc3frgev51ras", "status": "cancelled", "plan": {"id": "01m3ypwrhqvabc00wv89eb5qez", "code": "pro", "name": "Pro", "price_cents": 4900, "currency": "USD", "features": [{"key": "max_users", "enabled": true, "limit": 25}, {"key": "max_customers", "enabled": true, "limit": 5000}, {"key": "api_rate_per_minute", "enabled": true, "limit": 600}, {"key": "customer_export", "enabled": true, "limit": null}, {"key": "analytics_trends", "enabled": true, "limit": null}]}, "trial_ends_at": null, "current_period_start": "2026-10-02T16:24:25Z", "current_period_end": "2026-11-02T16:24:25Z", "cancelled_at": "2026-10-03T09:00:00Z", "ends_at": "2026-11-02T16:24:25Z", "is_usable": true, "usage": {"users": {"used": 3, "limit": 25, "over_limit": false}, "customers": {"used": 200, "limit": 5000, "over_limit": false}}}}
     * @response 401 scenario="Missing, invalid, or expired token" {"error": {"code": "UNAUTHENTICATED", "message": "Unauthenticated.", "details": {}}}
     * @response 403 scenario="No permission, or wrong user type for this route" {"error": {"code": "FORBIDDEN", "message": "This action is unauthorized.", "details": {}}}
     * @response 403 scenario="Company suspended" {"error": {"code": "TENANT_SUSPENDED", "message": "Your company account is suspended.", "details": {}}}
     * @response 429 scenario="Rate limit hit (Retry-After header is set)" {"error": {"code": "RATE_LIMITED", "message": "Too many requests. Please slow down.", "details": {}}}
     * @response 409 scenario="Change not allowed from the current state" {"error": {"code": "INVALID_SUBSCRIPTION_TRANSITION", "message": "A free plan cannot be cancelled.", "details": {}}}
     */
    public function cancel(): SubscriptionResource
    {
        Gate::authorize('manage', Subscription::class);

        return $this->toResource($this->subscriptions->cancel());
    }

    /** @param array{subscription: Subscription, usage: array<string, array{used: int, limit: int|null, over_limit: bool}>} $overview */
    private function toResource(array $overview): SubscriptionResource
    {
        return SubscriptionResource::make($overview['subscription'])->withUsage($overview['usage']);
    }
}
