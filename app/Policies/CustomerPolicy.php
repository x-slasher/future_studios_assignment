<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Permission;
use App\Models\Customer;
use App\Models\User;

class CustomerPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(Permission::CustomersView->value);
    }

    public function view(User $user, Customer $customer): bool
    {
        return $user->hasPermissionTo(Permission::CustomersView->value) && $user->tenant_id === $customer->tenant_id;
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permission::CustomersCreate->value);
    }

    public function update(User $user, Customer $customer): bool
    {
        return $user->hasPermissionTo(Permission::CustomersUpdate->value) && $user->tenant_id === $customer->tenant_id;
    }

    public function delete(User $user, Customer $customer): bool
    {
        return $user->hasPermissionTo(Permission::CustomersDelete->value) && $user->tenant_id === $customer->tenant_id;
    }
}
