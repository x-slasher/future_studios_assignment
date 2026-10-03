<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\FeatureGate;
use App\DTOs\CreateUserData;
use App\DTOs\UpdateUserData;
use App\DTOs\UserFilter;
use App\Enums\FeatureKey;
use App\Enums\TenantRole;
use App\Events\UserCreated;
use App\Events\UserDeleted;
use App\Events\UserUpdated;
use App\Exceptions\DuplicateEmail;
use App\Exceptions\LastOwnerException;
use App\Models\User;
use App\Repositories\Contracts\TenantRepositoryInterface;
use App\Repositories\Contracts\UsageRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Support\Tenancy\TenantContext;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class UserService
{
    public function __construct(
        private readonly UserRepositoryInterface $users,
        private readonly TenantRepositoryInterface $tenants,
        private readonly UsageRepositoryInterface $usage,
        private readonly FeatureGate $features,
        private readonly TenantContext $context,
    ) {}

    /** @return LengthAwarePaginator<int, User> */
    public function list(UserFilter $filter): LengthAwarePaginator
    {
        return $this->users->paginateForCurrentTenant($filter);
    }

    public function show(User $user): User
    {
        return $this->users->loadRoles($user);
    }

    public function create(CreateUserData $data): User
    {
        return DB::transaction(function () use ($data): User {
            // Lock the tenant row so two parallel creates cannot both pass the limit check.
            $tenant = $this->tenants->findLocked($this->context->id());
            $this->features->ensureWithinLimit($tenant, FeatureKey::MaxUsers, $this->usage->userCount());

            $user = $this->insert($tenant->id, $data);
            $user->syncRoles([$data->role->value]);

            UserCreated::dispatch($tenant->id, $user->id, $user->email);

            return $this->users->loadRoles($user);
        });
    }

    public function update(User $user, UpdateUserData $data): User
    {
        return DB::transaction(function () use ($user, $data): User {
            $this->tenants->findLocked($this->context->id());
            $this->ensureKeepsAnOwner($user, $data);

            $user = $this->users->update($user, array_filter(
                ['name' => $data->name, 'is_active' => $data->isActive],
                fn (mixed $value): bool => $value !== null,
            ));
            $this->applyRoleAndAccess($user, $data);

            UserUpdated::dispatch($user->tenant_id, $user->id);

            return $this->users->loadRoles($user);
        });
    }

    public function delete(User $user): void
    {
        DB::transaction(function () use ($user): void {
            $this->tenants->findLocked($this->context->id());

            if ($this->isActiveOwner($user) && $this->users->ownerCount() <= 1) {
                throw new LastOwnerException;
            }

            $this->users->revokeTokens($user);
            $this->users->delete($user);

            UserDeleted::dispatch($user->tenant_id, $user->id);
        });
    }

    private function insert(int $tenantId, CreateUserData $data): User
    {
        try {
            return $this->users->create([
                'tenant_id' => $tenantId,
                'name' => $data->name,
                'email' => $data->email,
                'password' => Str::password(32),
                'is_active' => true,
            ]);
        } catch (UniqueConstraintViolationException) {
            throw new DuplicateEmail;
        }
    }

    private function ensureKeepsAnOwner(User $user, UpdateUserData $data): void
    {
        $losesOwnership = ($data->role !== null && $data->role !== TenantRole::Owner) || $data->isActive === false;

        if ($losesOwnership && $this->isActiveOwner($user) && $this->users->ownerCount() <= 1) {
            throw new LastOwnerException;
        }
    }

    private function applyRoleAndAccess(User $user, UpdateUserData $data): void
    {
        if ($data->role !== null) {
            $user->syncRoles([$data->role->value]);
        }

        if ($data->isActive === false) {
            $this->users->revokeTokens($user);
        }
    }

    private function isActiveOwner(User $user): bool
    {
        return $user->is_active && $user->hasRole(TenantRole::Owner->value);
    }
}
