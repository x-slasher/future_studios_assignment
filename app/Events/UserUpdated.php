<?php

declare(strict_types=1);

namespace App\Events;

use App\Events\Contracts\TenantDataChanged;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;

final class UserUpdated implements ShouldDispatchAfterCommit, TenantDataChanged
{
    use Dispatchable;

    public function __construct(
        public readonly int $tenantId,
        public readonly int $userId,
    ) {}

    public function tenantId(): int
    {
        return $this->tenantId;
    }
}
