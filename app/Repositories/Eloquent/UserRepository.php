<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\DTOs\UserFilter;
use App\Enums\TenantRole;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Support\Tenancy\TenantContext;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Sanctum\PersonalAccessToken;

final class UserRepository implements UserRepositoryInterface
{
    private const array SORTABLE = ['created_at', 'name'];

    public function __construct(private readonly TenantContext $context) {}

    public function findActiveByEmail(string $email): ?User
    {
        return User::query()
            ->where('email', $email)
            ->where('is_active', true)
            ->with('tenant')
            ->first();
    }

    public function paginateForCurrentTenant(UserFilter $filter): LengthAwarePaginator
    {
        $sort = in_array($filter->sortField, self::SORTABLE, true) ? $filter->sortField : 'created_at';

        return $this->forCurrentTenant()
            ->when($filter->role, fn (Builder $q, TenantRole $role): Builder => $this->withRole($q, $role))
            ->when($filter->isActive !== null, fn (Builder $q): Builder => $q->where('is_active', $filter->isActive))
            ->when($filter->search, fn (Builder $q, string $term): Builder => $q->where(
                fn (Builder $q): Builder => $q->where('name', 'like', $this->prefix($term))
                    ->orWhere('email', 'like', $this->prefix($term)),
            ))
            ->with('roles')
            ->orderBy($sort, $filter->sortDirection)
            ->orderBy('id', $filter->sortDirection)
            ->paginate($filter->perPage)
            ->withQueryString();
    }

    public function create(array $attributes): User
    {
        return User::query()->create($attributes);
    }

    public function update(User $user, array $attributes): User
    {
        $user->update($attributes);

        return $user;
    }

    public function delete(User $user): void
    {
        $user->delete();
    }

    public function ownerCount(): int
    {
        return $this->withRole($this->forCurrentTenant(), TenantRole::Owner)
            ->where('is_active', true)
            ->count();
    }

    public function loadRoles(User $user): User
    {
        return $user->load('roles');
    }

    public function loadProfile(User $user): User
    {
        return $user->load(['tenant', 'roles']);
    }

    public function revokeTokens(User $user): void
    {
        $user->tokens()->delete();
    }

    public function revokeCurrentToken(User $user): void
    {
        $user->currentAccessToken()->delete();
    }

    public function revokeTokensForTenant(int $tenantId): void
    {
        PersonalAccessToken::query()
            ->where('tokenable_type', (new User)->getMorphClass())
            ->whereIn('tokenable_id', User::query()->withTrashed()->where('tenant_id', $tenantId)->select('id'))
            ->delete();
    }

    /** @return Builder<User> */
    private function forCurrentTenant(): Builder
    {
        return User::query()->where('tenant_id', $this->context->id());
    }

    /**
     * @param  Builder<User>  $query
     * @return Builder<User>
     */
    private function withRole(Builder $query, TenantRole $role): Builder
    {
        return $query->whereHas('roles', fn (Builder $q): Builder => $q->where('name', $role->value));
    }

    private function prefix(string $term): string
    {
        return addcslashes($term, '\\%_').'%';
    }
}
