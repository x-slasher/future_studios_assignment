<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Enums\TenantRole;

final readonly class UpdateUserData
{
    public function __construct(
        public ?string $name,
        public ?TenantRole $role,
        public ?bool $isActive,
    ) {}
}
