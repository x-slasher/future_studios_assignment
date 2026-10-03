<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin User */
class UserResource extends JsonResource
{
    /** @var list<string>|null */
    private ?array $permissions = null;

    /** @param  list<string>  $permissions */
    public function withPermissions(array $permissions): static
    {
        $this->permissions = $permissions;

        return $this;
    }

    /** @return array<string, mixed> */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->ulid,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->roles->first()?->name,
            'is_active' => $this->is_active,
            'last_login_at' => $this->last_login_at?->toIso8601ZuluString(),
            'created_at' => $this->created_at->toIso8601ZuluString(),
            'tenant' => TenantResource::make($this->whenLoaded('tenant')),
            'permissions' => $this->when($this->permissions !== null, $this->permissions),
        ];
    }
}
