<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Enums\TenantStatus;

final readonly class TenantFilter
{
    public function __construct(
        public ?TenantStatus $status,
        public ?string $planCode,
        public ?string $search,
        public string $sortField,
        public string $sortDirection,
        public int $perPage,
    ) {}
}
