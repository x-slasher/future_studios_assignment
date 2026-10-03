<?php

declare(strict_types=1);

namespace App\DTOs;

final readonly class RegisterTenantData
{
    public function __construct(
        public string $companyName,
        public string $name,
        public string $email,
        public string $password,
        public ?string $planCode,
    ) {}
}
