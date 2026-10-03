<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Subscription;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Subscription */
class SubscriptionResource extends JsonResource
{
    /** @var array<string, array{used: int, limit: int|null, over_limit: bool}>|null */
    private ?array $usage = null;

    /** @param  array<string, array{used: int, limit: int|null, over_limit: bool}>  $usage */
    public function withUsage(array $usage): static
    {
        $this->usage = $usage;

        return $this;
    }

    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->ulid,
            'status' => $this->status->value,
            'plan' => PlanResource::make($this->plan),
            'trial_ends_at' => $this->trial_ends_at?->toIso8601ZuluString(),
            'current_period_start' => $this->current_period_start?->toIso8601ZuluString(),
            'current_period_end' => $this->current_period_end?->toIso8601ZuluString(),
            'cancelled_at' => $this->cancelled_at?->toIso8601ZuluString(),
            'ends_at' => $this->ends_at?->toIso8601ZuluString(),
            'is_usable' => $this->isUsable(CarbonImmutable::now()),
            'usage' => $this->when($this->usage !== null, $this->usage),
        ];
    }
}
