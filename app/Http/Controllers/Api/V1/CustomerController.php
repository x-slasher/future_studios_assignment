<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\ListCustomersRequest;
use App\Http\Requests\Customers\StoreCustomerRequest;
use App\Http\Requests\Customers\UpdateCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Services\CustomerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

/**
 * @group Customers
 *
 * Your company customers.
 */
final class CustomerController extends Controller
{
    public function __construct(private readonly CustomerService $customers) {}

    /**
     * List customers
     *
     * Your company customers only. Needs customers.view.
     *
     * @authenticated
     *
     * @response 200 {"data": [{"id": "01m3yq2mys56kee6prc9xsn2ac", "name": "Karim Store", "email": "karim@store.test", "phone": "+8801700000000", "company_name": "Karim Ltd", "status": "active", "created_by": {"id": "01m3ypws7j18e4984mfb5kkz73", "name": "Acme Ltd Owner"}, "created_at": "2026-10-02T16:27:37Z", "updated_at": "2026-10-02T16:27:37Z"}], "links": {"first": "http://localhost:8000/api/v1/customers?page=1", "last": "http://localhost:8000/api/v1/customers?page=14", "prev": null, "next": "http://localhost:8000/api/v1/customers?page=2"}, "meta": {"current_page": 1, "from": 1, "last_page": 14, "path": "http://localhost:8000/api/v1/customers", "per_page": 15, "to": 15, "total": 200}}
     * @response 401 scenario="Missing, invalid, or expired token" {"error": {"code": "UNAUTHENTICATED", "message": "Unauthenticated.", "details": {}}}
     * @response 403 scenario="No permission, or wrong user type for this route" {"error": {"code": "FORBIDDEN", "message": "This action is unauthorized.", "details": {}}}
     * @response 403 scenario="Company suspended" {"error": {"code": "TENANT_SUSPENDED", "message": "Your company account is suspended.", "details": {}}}
     * @response 429 scenario="Rate limit hit (Retry-After header is set)" {"error": {"code": "RATE_LIMITED", "message": "Too many requests. Please slow down.", "details": {}}}
     * @response 422 scenario="Validation failed" {"error": {"code": "VALIDATION_FAILED", "message": "The given data was invalid.", "details": {"filter": ["The filter field must be an array with only the keys: status, search, created_from, created_to."]}}}
     */
    public function index(ListCustomersRequest $request): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', Customer::class);

        return CustomerResource::collection($this->customers->list($request->toFilter()));
    }

    /**
     * Create a customer
     *
     * Counts against the plan max_customers. Any tenant_id in the body is ignored.
     *
     * @authenticated
     *
     * @response 201 {"data": {"id": "01m3yq2mys56kee6prc9xsn2ac", "name": "Karim Store", "email": "karim@store.test", "phone": "+8801700000000", "company_name": "Karim Ltd", "status": "active", "created_by": {"id": "01m3ypws7j18e4984mfb5kkz73", "name": "Acme Ltd Owner"}, "created_at": "2026-10-02T16:27:37Z", "updated_at": "2026-10-02T16:27:37Z"}}
     * @response 401 scenario="Missing, invalid, or expired token" {"error": {"code": "UNAUTHENTICATED", "message": "Unauthenticated.", "details": {}}}
     * @response 403 scenario="No permission, or wrong user type for this route" {"error": {"code": "FORBIDDEN", "message": "This action is unauthorized.", "details": {}}}
     * @response 403 scenario="Company suspended" {"error": {"code": "TENANT_SUSPENDED", "message": "Your company account is suspended.", "details": {}}}
     * @response 429 scenario="Rate limit hit (Retry-After header is set)" {"error": {"code": "RATE_LIMITED", "message": "Too many requests. Please slow down.", "details": {}}}
     * @response 403 scenario="Plan customer limit reached" {"error": {"code": "PLAN_LIMIT_EXCEEDED", "message": "Your plan allows 500 customers. Upgrade your plan to add more.", "details": {"feature": "max_customers", "limit": 500, "current": 500}}}
     * @response 402 scenario="Subscription not usable (writes only)" {"error": {"code": "SUBSCRIPTION_INACTIVE", "message": "Your subscription is not active. Renew it to make changes.", "details": {}}}
     * @response 422 scenario="Validation failed" {"error": {"code": "VALIDATION_FAILED", "message": "The given data was invalid.", "details": {"email": ["The email has already been taken."]}}}
     */
    public function store(StoreCustomerRequest $request): JsonResponse
    {
        Gate::authorize('create', Customer::class);

        $customer = $this->customers->create($request->toDto(), $request->user());

        return CustomerResource::make($customer)->response()->setStatusCode(201);
    }

    /**
     * Show a customer
     *
     * A customer from another company returns 404.
     *
     * @authenticated
     *
     * @response 200 {"data": {"id": "01m3yq2mys56kee6prc9xsn2ac", "name": "Karim Store", "email": "karim@store.test", "phone": "+8801700000000", "company_name": "Karim Ltd", "status": "active", "created_by": {"id": "01m3ypws7j18e4984mfb5kkz73", "name": "Acme Ltd Owner"}, "created_at": "2026-10-02T16:27:37Z", "updated_at": "2026-10-02T16:27:37Z"}}
     * @response 401 scenario="Missing, invalid, or expired token" {"error": {"code": "UNAUTHENTICATED", "message": "Unauthenticated.", "details": {}}}
     * @response 403 scenario="No permission, or wrong user type for this route" {"error": {"code": "FORBIDDEN", "message": "This action is unauthorized.", "details": {}}}
     * @response 403 scenario="Company suspended" {"error": {"code": "TENANT_SUSPENDED", "message": "Your company account is suspended.", "details": {}}}
     * @response 429 scenario="Rate limit hit (Retry-After header is set)" {"error": {"code": "RATE_LIMITED", "message": "Too many requests. Please slow down.", "details": {}}}
     * @response 404 scenario="Not found or belongs to another company" {"error": {"code": "NOT_FOUND", "message": "The requested resource was not found.", "details": {}}}
     */
    public function show(Customer $customer): CustomerResource
    {
        Gate::authorize('view', $customer);

        return CustomerResource::make($customer);
    }

    /**
     * Update a customer
     *
     * Send only the fields to change.
     *
     * @authenticated
     *
     * @response 200 {"data": {"id": "01m3yq2mys56kee6prc9xsn2ac", "name": "Karim Store", "email": "karim@store.test", "phone": "+8801700000000", "company_name": "Karim Ltd", "status": "inactive", "created_by": {"id": "01m3ypws7j18e4984mfb5kkz73", "name": "Acme Ltd Owner"}, "created_at": "2026-10-02T16:27:37Z", "updated_at": "2026-10-02T16:27:37Z"}}
     * @response 401 scenario="Missing, invalid, or expired token" {"error": {"code": "UNAUTHENTICATED", "message": "Unauthenticated.", "details": {}}}
     * @response 403 scenario="No permission, or wrong user type for this route" {"error": {"code": "FORBIDDEN", "message": "This action is unauthorized.", "details": {}}}
     * @response 403 scenario="Company suspended" {"error": {"code": "TENANT_SUSPENDED", "message": "Your company account is suspended.", "details": {}}}
     * @response 429 scenario="Rate limit hit (Retry-After header is set)" {"error": {"code": "RATE_LIMITED", "message": "Too many requests. Please slow down.", "details": {}}}
     * @response 404 scenario="Not found or belongs to another company" {"error": {"code": "NOT_FOUND", "message": "The requested resource was not found.", "details": {}}}
     * @response 402 scenario="Subscription not usable (writes only)" {"error": {"code": "SUBSCRIPTION_INACTIVE", "message": "Your subscription is not active. Renew it to make changes.", "details": {}}}
     * @response 422 scenario="Validation failed" {"error": {"code": "VALIDATION_FAILED", "message": "The given data was invalid.", "details": {"email": ["The email has already been taken."]}}}
     */
    public function update(UpdateCustomerRequest $request, Customer $customer): CustomerResource
    {
        Gate::authorize('update', $customer);

        return CustomerResource::make($this->customers->update($customer, $request->toDto()));
    }

    /**
     * Delete a customer
     *
     * Soft delete. The email can be used again. Needs customers.delete (members cannot).
     *
     * @authenticated
     *
     * @response 204 scenario="Deleted" ""
     * @response 401 scenario="Missing, invalid, or expired token" {"error": {"code": "UNAUTHENTICATED", "message": "Unauthenticated.", "details": {}}}
     * @response 403 scenario="No permission, or wrong user type for this route" {"error": {"code": "FORBIDDEN", "message": "This action is unauthorized.", "details": {}}}
     * @response 403 scenario="Company suspended" {"error": {"code": "TENANT_SUSPENDED", "message": "Your company account is suspended.", "details": {}}}
     * @response 429 scenario="Rate limit hit (Retry-After header is set)" {"error": {"code": "RATE_LIMITED", "message": "Too many requests. Please slow down.", "details": {}}}
     * @response 404 scenario="Not found or belongs to another company" {"error": {"code": "NOT_FOUND", "message": "The requested resource was not found.", "details": {}}}
     * @response 402 scenario="Subscription not usable (writes only)" {"error": {"code": "SUBSCRIPTION_INACTIVE", "message": "Your subscription is not active. Renew it to make changes.", "details": {}}}
     */
    public function destroy(Customer $customer): Response
    {
        Gate::authorize('delete', $customer);

        $this->customers->delete($customer);

        return response()->noContent();
    }
}
