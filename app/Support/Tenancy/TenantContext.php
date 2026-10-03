<?php

declare(strict_types=1);

namespace App\Support\Tenancy;

use App\Exceptions\TenantContextMissing;
use App\Models\Tenant;

final class TenantContext
{
    private ?Tenant $tenant = null;

    public function set(Tenant $tenant): void
    {
        $this->tenant = $tenant;
        setPermissionsTeamId($tenant->id);
    }

    public function clear(): void
    {
        $this->tenant = null;
        setPermissionsTeamId(null);
    }

    public function has(): bool
    {
        return $this->tenant !== null;
    }

    public function tenant(): Tenant
    {
        return $this->tenant ?? throw new TenantContextMissing;
    }

    public function id(): int
    {
        return $this->tenant()->id;
    }

    public function run(Tenant $tenant, callable $callback): mixed
    {
        $previous = $this->tenant;
        $this->set($tenant);

        try {
            return $callback();
        } finally {
            $previous !== null ? $this->set($previous) : $this->clear();
        }
    }
}
