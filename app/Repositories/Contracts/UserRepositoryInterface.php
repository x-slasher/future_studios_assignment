<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\DTOs\UserFilter;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function findActiveByEmail(string $email): ?User;

    /** @return LengthAwarePaginator<int, User> */
    public function paginateForCurrentTenant(UserFilter $filter): LengthAwarePaginator;

    /** @param array<string, mixed> $attributes */
    public function create(array $attributes): User;

    /** @param array<string, mixed> $attributes */
    public function update(User $user, array $attributes): User;

    public function delete(User $user): void;

    public function ownerCount(): int;

    public function loadRoles(User $user): User;

    public function loadProfile(User $user): User;

    public function revokeTokens(User $user): void;

    public function revokeCurrentToken(User $user): void;

    public function revokeTokensForTenant(int $tenantId): void;
}
