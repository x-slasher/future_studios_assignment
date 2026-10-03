<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Enums\TenantRole;

final readonly class UserFilter
{
    public function __construct(
        public ?TenantRole $role,
        public ?bool $isActive,
        public ?string $search,
        public string $sortField,
        public string $sortDirection,
        public int $perPage,
    ) {}
}
