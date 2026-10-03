<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Enums\TenantRole;

final readonly class CreateUserData
{
    public function __construct(
        public string $name,
        public string $email,
        public TenantRole $role,
    ) {}
}
