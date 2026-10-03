<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Enums\CustomerStatus;

final readonly class UpdateCustomerData
{
    /** @param list<string> $provided */
    public function __construct(
        public ?string $name,
        public ?string $email,
        public ?string $phone,
        public ?string $companyName,
        public ?CustomerStatus $status,
        public array $provided,
    ) {}
}
