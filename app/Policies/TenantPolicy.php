<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Permission;
use App\Models\User;

class TenantPolicy
{
    public function view(User $user): bool
    {
        return $user->hasPermissionTo(Permission::TenantView->value);
    }

    public function update(User $user): bool
    {
        return $user->hasPermissionTo(Permission::TenantUpdate->value);
    }
}
