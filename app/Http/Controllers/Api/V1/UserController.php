<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\ListUsersRequest;
use App\Http\Requests\Users\StoreUserRequest;
use App\Http\Requests\Users\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

/**
 * @group Users
 *
 * Staff users of your company.
 */
final class UserController extends Controller
{
    public function __construct(private readonly UserService $users) {}

    /**
     * List users
     *
     * Your company users only. Needs users.view.
     *
     * @authenticated
     *
     * @response 200 {"data": [{"id": "01m3ypwsxybe30vqk6h7k91gbr", "name": "Acme Member", "email": "member@acme.test", "role": "member", "is_active": true, "last_login_at": null, "created_at": "2026-10-02T16:24:26Z"}], "links": {"first": "http://localhost:8000/api/v1/users?page=1", "last": "http://localhost:8000/api/v1/users?page=14", "prev": null, "next": "http://localhost:8000/api/v1/users?page=2"}, "meta": {"current_page": 1, "from": 1, "last_page": 14, "path": "http://localhost:8000/api/v1/users", "per_page": 15, "to": 15, "total": 200}}
     * @response 401 scenario="Missing, invalid, or expired token" {"error": {"code": "UNAUTHENTICATED", "message": "Unauthenticated.", "details": {}}}
     * @response 403 scenario="No permission, or wrong user type for this route" {"error": {"code": "FORBIDDEN", "message": "This action is unauthorized.", "details": {}}}
     * @response 403 scenario="Company suspended" {"error": {"code": "TENANT_SUSPENDED", "message": "Your company account is suspended.", "details": {}}}
     * @response 429 scenario="Rate limit hit (Retry-After header is set)" {"error": {"code": "RATE_LIMITED", "message": "Too many requests. Please slow down.", "details": {}}}
     * @response 422 scenario="Validation failed" {"error": {"code": "VALIDATION_FAILED", "message": "The given data was invalid.", "details": {"sort": ["The selected sort is invalid."]}}}
     */
    public function index(ListUsersRequest $request): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', User::class);

        return UserResource::collection($this->users->list($request->toFilter()));
    }

    /**
     * Create a user
     *
     * The user gets a random password and an email with a token to set their own. Counts against the plan max_users. Only an owner can assign the owner role.
     *
     * @authenticated
     *
     * @response 201 {"data": {"id": "01m3ypwsxybe30vqk6h7k91gbr", "name": "Acme Member", "email": "member@acme.test", "role": "member", "is_active": true, "last_login_at": null, "created_at": "2026-10-02T16:24:26Z"}}
     * @response 401 scenario="Missing, invalid, or expired token" {"error": {"code": "UNAUTHENTICATED", "message": "Unauthenticated.", "details": {}}}
     * @response 403 scenario="No permission, or wrong user type for this route" {"error": {"code": "FORBIDDEN", "message": "This action is unauthorized.", "details": {}}}
     * @response 403 scenario="Company suspended" {"error": {"code": "TENANT_SUSPENDED", "message": "Your company account is suspended.", "details": {}}}
     * @response 429 scenario="Rate limit hit (Retry-After header is set)" {"error": {"code": "RATE_LIMITED", "message": "Too many requests. Please slow down.", "details": {}}}
     * @response 403 scenario="Plan user limit reached" {"error": {"code": "PLAN_LIMIT_EXCEEDED", "message": "Your plan allows 5 users. Upgrade your plan to add more.", "details": {"feature": "max_users", "limit": 5, "current": 5}}}
     * @response 402 scenario="Subscription not usable (writes only)" {"error": {"code": "SUBSCRIPTION_INACTIVE", "message": "Your subscription is not active. Renew it to make changes.", "details": {}}}
     * @response 422 scenario="Validation failed" {"error": {"code": "VALIDATION_FAILED", "message": "The given data was invalid.", "details": {"email": ["The email has already been taken."]}}}
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $data = $request->toDto();
        Gate::authorize('create', [User::class, $data->role]);

        return UserResource::make($this->users->create($data))->response()->setStatusCode(201);
    }

    /**
     * Show a user
     *
     * A user from another company returns 404.
     *
     * @authenticated
     *
     * @response 200 {"data": {"id": "01m3ypwsxybe30vqk6h7k91gbr", "name": "Acme Member", "email": "member@acme.test", "role": "member", "is_active": true, "last_login_at": null, "created_at": "2026-10-02T16:24:26Z"}}
     * @response 401 scenario="Missing, invalid, or expired token" {"error": {"code": "UNAUTHENTICATED", "message": "Unauthenticated.", "details": {}}}
     * @response 403 scenario="No permission, or wrong user type for this route" {"error": {"code": "FORBIDDEN", "message": "This action is unauthorized.", "details": {}}}
     * @response 403 scenario="Company suspended" {"error": {"code": "TENANT_SUSPENDED", "message": "Your company account is suspended.", "details": {}}}
     * @response 429 scenario="Rate limit hit (Retry-After header is set)" {"error": {"code": "RATE_LIMITED", "message": "Too many requests. Please slow down.", "details": {}}}
     * @response 404 scenario="Not found or belongs to another company" {"error": {"code": "NOT_FOUND", "message": "The requested resource was not found.", "details": {}}}
     */
    public function show(User $user): UserResource
    {
        Gate::authorize('view', $user);

        return UserResource::make($this->users->show($user));
    }

    /**
     * Update a user
     *
     * An admin cannot change an owner. Deactivating revokes all the user tokens. The last active owner cannot be demoted or deactivated.
     *
     * @authenticated
     *
     * @response 200 {"data": {"id": "01m3ypwsxybe30vqk6h7k91gbr", "name": "Acme Member", "email": "member@acme.test", "role": "admin", "is_active": true, "last_login_at": null, "created_at": "2026-10-02T16:24:26Z"}}
     * @response 401 scenario="Missing, invalid, or expired token" {"error": {"code": "UNAUTHENTICATED", "message": "Unauthenticated.", "details": {}}}
     * @response 403 scenario="No permission, or wrong user type for this route" {"error": {"code": "FORBIDDEN", "message": "This action is unauthorized.", "details": {}}}
     * @response 403 scenario="Company suspended" {"error": {"code": "TENANT_SUSPENDED", "message": "Your company account is suspended.", "details": {}}}
     * @response 429 scenario="Rate limit hit (Retry-After header is set)" {"error": {"code": "RATE_LIMITED", "message": "Too many requests. Please slow down.", "details": {}}}
     * @response 404 scenario="Not found or belongs to another company" {"error": {"code": "NOT_FOUND", "message": "The requested resource was not found.", "details": {}}}
     * @response 402 scenario="Subscription not usable (writes only)" {"error": {"code": "SUBSCRIPTION_INACTIVE", "message": "Your subscription is not active. Renew it to make changes.", "details": {}}}
     * @response 409 scenario="Would leave the company without an owner" {"error": {"code": "LAST_OWNER", "message": "A company must keep at least one active owner.", "details": {}}}
     * @response 422 scenario="Validation failed" {"error": {"code": "VALIDATION_FAILED", "message": "The given data was invalid.", "details": {"role": ["The selected role is invalid."]}}}
     */
    public function update(UpdateUserRequest $request, User $user): UserResource
    {
        $data = $request->toDto();
        Gate::authorize('update', [$user, $data->role]);

        return UserResource::make($this->users->update($user, $data));
    }

    /**
     * Delete a user
     *
     * Soft delete. Revokes the user tokens. Nobody can delete themselves, and an admin cannot delete an owner.
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
     * @response 409 scenario="Would leave the company without an owner" {"error": {"code": "LAST_OWNER", "message": "A company must keep at least one active owner.", "details": {}}}
     */
    public function destroy(User $user): Response
    {
        Gate::authorize('delete', $user);

        $this->users->delete($user);

        return response()->noContent();
    }
}
