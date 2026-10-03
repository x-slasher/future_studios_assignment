<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Permission;
use App\Enums\TenantRole;
use App\Models\User;

class UserPolicy
{
    public function viewAny(User $actor): bool
    {
        return $actor->hasPermissionTo(Permission::UsersView->value);
    }

    public function view(User $actor, User $target): bool
    {
        return $actor->hasPermissionTo(Permission::UsersView->value) && $this->sameTenant($actor, $target);
    }

    public function create(User $actor, TenantRole $role): bool
    {
        return $actor->hasPermissionTo(Permission::UsersCreate->value)
            && ($role !== TenantRole::Owner || $this->isOwner($actor));
    }

    public function update(User $actor, User $target, ?TenantRole $newRole = null): bool
    {
        return $actor->hasPermissionTo(Permission::UsersUpdate->value)
            && $this->sameTenant($actor, $target)
            && $this->mayManage($actor, $target)
            && ($newRole !== TenantRole::Owner || $this->isOwner($actor));
    }

    public function delete(User $actor, User $target): bool
    {
        return $actor->hasPermissionTo(Permission::UsersDelete->value)
            && $this->sameTenant($actor, $target)
            && $actor->id !== $target->id
            && $this->mayManage($actor, $target);
    }

    private function mayManage(User $actor, User $target): bool
    {
        return ! $this->isOwner($target) || $this->isOwner($actor);
    }

    private function isOwner(User $user): bool
    {
        return $user->hasRole(TenantRole::Owner->value);
    }

    private function sameTenant(User $actor, User $target): bool
    {
        return $actor->tenant_id === $target->tenant_id;
    }
}
