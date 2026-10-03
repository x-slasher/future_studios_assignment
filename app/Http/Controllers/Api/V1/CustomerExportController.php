<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customers\ExportCustomersRequest;
use App\Http\Resources\CustomerExportResource;
use App\Models\CustomerExport;
use App\Services\CustomerExportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * @group Customer Exports
 *
 * Asynchronous CSV exports of customers.
 */
final class CustomerExportController extends Controller
{
    public function __construct(private readonly CustomerExportService $exports) {}

    /**
     * Request a CSV export
     *
     * Takes the same filters as the customer list. A queued job builds the CSV; poll the export until download_url is set. Needs the customer_export plan feature and customers.export. Limited to 5 per hour per company.
     *
     * @authenticated
     *
     * @response 202 {"data": {"id": "01m3yq2mzrzmdpxqgw955qbt99", "status": "pending", "row_count": null, "download_url": null, "error_message": null, "created_at": "2026-10-02T16:27:37Z", "completed_at": null}}
     * @response 401 scenario="Missing, invalid, or expired token" {"error": {"code": "UNAUTHENTICATED", "message": "Unauthenticated.", "details": {}}}
     * @response 403 scenario="No permission, or wrong user type for this route" {"error": {"code": "FORBIDDEN", "message": "This action is unauthorized.", "details": {}}}
     * @response 403 scenario="Company suspended" {"error": {"code": "TENANT_SUSPENDED", "message": "Your company account is suspended.", "details": {}}}
     * @response 429 scenario="Rate limit hit (Retry-After header is set)" {"error": {"code": "RATE_LIMITED", "message": "Too many requests. Please slow down.", "details": {}}}
     * @response 403 scenario="Plan has no customer export" {"error": {"code": "FEATURE_NOT_AVAILABLE", "message": "Your plan does not include this feature. Upgrade your plan to use it.", "details": {"feature": "customer_export"}}}
     * @response 402 scenario="Subscription not usable (writes only)" {"error": {"code": "SUBSCRIPTION_INACTIVE", "message": "Your subscription is not active. Renew it to make changes.", "details": {}}}
     * @response 422 scenario="Validation failed" {"error": {"code": "VALIDATION_FAILED", "message": "The given data was invalid.", "details": {"filter.status": ["The selected filter.status is invalid."]}}}
     */
    public function store(ExportCustomersRequest $request): JsonResponse
    {
        Gate::authorize('create', CustomerExport::class);

        $export = $this->exports->request($request->toFilter(), $request->user());

        return CustomerExportResource::make($export)->response()->setStatusCode(202);
    }

    /**
     * Show an export
     *
     * download_url is null until the status is completed.
     *
     * @authenticated
     *
     * @response 200 {"data": {"id": "01m3yq2mzrzmdpxqgw955qbt99", "status": "completed", "row_count": 181, "download_url": "http://localhost:8000/api/v1/customers/exports/01m3yq2mzrzmdpxqgw955qbt99/download", "error_message": null, "created_at": "2026-10-02T16:27:37Z", "completed_at": "2026-10-02T16:27:38Z"}}
     * @response 401 scenario="Missing, invalid, or expired token" {"error": {"code": "UNAUTHENTICATED", "message": "Unauthenticated.", "details": {}}}
     * @response 403 scenario="No permission, or wrong user type for this route" {"error": {"code": "FORBIDDEN", "message": "This action is unauthorized.", "details": {}}}
     * @response 403 scenario="Company suspended" {"error": {"code": "TENANT_SUSPENDED", "message": "Your company account is suspended.", "details": {}}}
     * @response 429 scenario="Rate limit hit (Retry-After header is set)" {"error": {"code": "RATE_LIMITED", "message": "Too many requests. Please slow down.", "details": {}}}
     * @response 403 scenario="Plan has no customer export" {"error": {"code": "FEATURE_NOT_AVAILABLE", "message": "Your plan does not include this feature. Upgrade your plan to use it.", "details": {"feature": "customer_export"}}}
     * @response 404 scenario="Not found or belongs to another company" {"error": {"code": "NOT_FOUND", "message": "The requested resource was not found.", "details": {}}}
     */
    public function show(CustomerExport $export): CustomerExportResource
    {
        Gate::authorize('view', $export);

        return CustomerExportResource::make($export);
    }

    /**
     * Download an export
     *
     * Streams the CSV file. Cells that start with =, +, -, or @ are prefixed with a quote, so spreadsheets do not run them as formulas.
     *
     * @authenticated
     *
     * @response 200 scenario="CSV file" "id,name,email,phone,company_name,status,created_at\n01m3yq2mys56kee6prc9xsn2ac,\"Karim Store\",karim@store.test,'+8801700000000,\"Karim Ltd\",active,2026-10-02T16:27:37Z"
     * @response 401 scenario="Missing, invalid, or expired token" {"error": {"code": "UNAUTHENTICATED", "message": "Unauthenticated.", "details": {}}}
     * @response 403 scenario="No permission, or wrong user type for this route" {"error": {"code": "FORBIDDEN", "message": "This action is unauthorized.", "details": {}}}
     * @response 403 scenario="Company suspended" {"error": {"code": "TENANT_SUSPENDED", "message": "Your company account is suspended.", "details": {}}}
     * @response 429 scenario="Rate limit hit (Retry-After header is set)" {"error": {"code": "RATE_LIMITED", "message": "Too many requests. Please slow down.", "details": {}}}
     * @response 403 scenario="Plan has no customer export" {"error": {"code": "FEATURE_NOT_AVAILABLE", "message": "Your plan does not include this feature. Upgrade your plan to use it.", "details": {"feature": "customer_export"}}}
     * @response 404 scenario="Not found or belongs to another company" {"error": {"code": "NOT_FOUND", "message": "The requested resource was not found.", "details": {}}}
     * @response 409 scenario="Export not completed yet" {"error": {"code": "EXPORT_NOT_READY", "message": "The export is not ready yet.", "details": {}}}
     */
    public function download(CustomerExport $export): StreamedResponse
    {
        Gate::authorize('view', $export);

        $path = $this->exports->downloadPath($export);

        return Storage::disk($this->exports->disk())->download($path, "customers-{$export->ulid}.csv");
    }
}
