<?php

declare(strict_types=1);

namespace App\Enums;

enum TenantRole: string
{
    case Owner = 'owner';
    case Admin = 'admin';
    case Member = 'member';

    /** @return list<Permission> */
    public function permissions(): array
    {
        return match ($this) {
            self::Owner => Permission::cases(),
            self::Admin => [
                Permission::TenantView,
                Permission::UsersView,
                Permission::UsersCreate,
                Permission::UsersUpdate,
                Permission::UsersDelete,
                Permission::CustomersView,
                Permission::CustomersCreate,
                Permission::CustomersUpdate,
                Permission::CustomersDelete,
                Permission::CustomersExport,
                Permission::SubscriptionView,
                Permission::DashboardView,
            ],
            self::Member => [
                Permission::TenantView,
                Permission::UsersView,
                Permission::CustomersView,
                Permission::CustomersCreate,
                Permission::CustomersUpdate,
                Permission::SubscriptionView,
                Permission::DashboardView,
            ],
        };
    }
}
