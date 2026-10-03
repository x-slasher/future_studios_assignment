<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Enums\FeatureKey;

final readonly class PlanData
{
    /** @param list<array{key: FeatureKey, enabled: bool, limit: int|null}>|null $features */
    public function __construct(
        public ?string $code,
        public ?string $name,
        public ?int $priceCents,
        public ?string $currency,
        public ?bool $isActive,
        public ?int $sortOrder,
        public ?array $features,
    ) {}
}
