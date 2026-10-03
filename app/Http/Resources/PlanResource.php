<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Plan;
use App\Models\PlanFeature;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Plan */
class PlanResource extends JsonResource
{
    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->ulid,
            'code' => $this->code,
            'name' => $this->name,
            'price_cents' => $this->price_cents,
            'currency' => $this->currency,
            'features' => $this->features->map(fn (PlanFeature $feature): array => [
                'key' => $feature->feature_key->value,
                'enabled' => $feature->is_enabled,
                'limit' => $feature->limit_value,
            ])->values()->all(),
        ];
    }
}
