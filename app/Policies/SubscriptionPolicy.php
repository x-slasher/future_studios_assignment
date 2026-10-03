<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Permission;
use App\Models\User;

class SubscriptionPolicy
{
    public function view(User $user): bool
    {
        return $user->hasPermissionTo(Permission::SubscriptionView->value);
    }

    public function manage(User $user): bool
    {
        return $user->hasPermissionTo(Permission::SubscriptionManage->value);
    }
}
