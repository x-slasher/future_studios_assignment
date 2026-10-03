<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Permission;
use App\Models\CustomerExport;
use App\Models\User;

class CustomerExportPolicy
{
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(Permission::CustomersExport->value);
    }

    public function view(User $user, CustomerExport $export): bool
    {
        return $user->hasPermissionTo(Permission::CustomersExport->value) && $user->tenant_id === $export->tenant_id;
    }
}
