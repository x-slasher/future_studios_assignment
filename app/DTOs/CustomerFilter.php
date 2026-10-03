<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Enums\CustomerStatus;
use Carbon\CarbonImmutable;

final readonly class CustomerFilter
{
    public function __construct(
        public ?CustomerStatus $status,
        public ?string $search,
        public ?CarbonImmutable $createdFrom,
        public ?CarbonImmutable $createdTo,
        public string $sortField = 'created_at',
        public string $sortDirection = 'desc',
        public int $perPage = 15,
    ) {}
}
