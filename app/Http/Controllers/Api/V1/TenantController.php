<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\UpdateTenantRequest;
use App\Http\Resources\TenantResource;
use App\Models\Tenant;
use App\Services\TenantService;
use Illuminate\Support\Facades\Gate;

/**
 * @group Tenant
 *
 * Your company.
 */
final class TenantController extends Controller
{
    public function __construct(private readonly TenantService $tenants) {}

    /**
     * Show your company
     *
     * Needs the tenant.view permission.
     *
     * @authenticated
     *
     * @response 200 {"data": {"id": "01m3ypws0r7ercmyaecr9y5a12", "name": "Acme Ltd", "status": "active", "created_at": "2026-10-02T16:24:25Z"}}
     * @response 401 scenario="Missing, invalid, or expired token" {"error": {"code": "UNAUTHENTICATED", "message": "Unauthenticated.", "details": {}}}
     * @response 403 scenario="No permission, or wrong user type for this route" {"error": {"code": "FORBIDDEN", "message": "This action is unauthorized.", "details": {}}}
     * @response 403 scenario="Company suspended" {"error": {"code": "TENANT_SUSPENDED", "message": "Your company account is suspended.", "details": {}}}
     * @response 429 scenario="Rate limit hit (Retry-After header is set)" {"error": {"code": "RATE_LIMITED", "message": "Too many requests. Please slow down.", "details": {}}}
     */
    public function show(): TenantResource
    {
        Gate::authorize('view', Tenant::class);

        return TenantResource::make($this->tenants->current());
    }

    /**
     * Rename your company
     *
     * Needs the tenant.update permission (owner only).
     *
     * @authenticated
     *
     * @response 200 {"data": {"id": "01m3ypws0r7ercmyaecr9y5a12", "name": "Acme Limited", "status": "active", "created_at": "2026-10-02T16:24:25Z"}}
     * @response 401 scenario="Missing, invalid, or expired token" {"error": {"code": "UNAUTHENTICATED", "message": "Unauthenticated.", "details": {}}}
     * @response 403 scenario="No permission, or wrong user type for this route" {"error": {"code": "FORBIDDEN", "message": "This action is unauthorized.", "details": {}}}
     * @response 403 scenario="Company suspended" {"error": {"code": "TENANT_SUSPENDED", "message": "Your company account is suspended.", "details": {}}}
     * @response 429 scenario="Rate limit hit (Retry-After header is set)" {"error": {"code": "RATE_LIMITED", "message": "Too many requests. Please slow down.", "details": {}}}
     * @response 422 scenario="Validation failed" {"error": {"code": "VALIDATION_FAILED", "message": "The given data was invalid.", "details": {"name": ["The name field is required."]}}}
     */
    public function update(UpdateTenantRequest $request): TenantResource
    {
        Gate::authorize('update', Tenant::class);

        return TenantResource::make($this->tenants->update($request->toDto()));
    }
}
