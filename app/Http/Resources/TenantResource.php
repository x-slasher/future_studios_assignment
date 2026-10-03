<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Tenant */
class TenantResource extends JsonResource
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
            'name' => $this->name,
            'status' => $this->status->value,
            'created_at' => $this->created_at->toIso8601ZuluString(),
            'subscription' => SubscriptionResource::make($this->whenLoaded('subscription')),
            'usage' => $this->when($this->usage !== null, $this->usage),
        ];
    }
}
