<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\Concerns\HasPublicUlid;
use App\Support\Tenancy\TenantContext;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use HasPublicUlid;
    use HasRoles;
    use Notifiable;
    use SoftDeletes;

    /** @var list<string> */
    // is_platform_admin is left out on purpose: it must never be mass assignable.
    protected $fillable = [
        'tenant_id',
        'name',
        'email',
        'password',
        'is_active',
        'last_login_at',
    ];

    /** @var list<string> */
    protected $hidden = [
        'password',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_platform_admin' => 'boolean',
            'is_active' => 'boolean',
            'last_login_at' => 'immutable_datetime',
            'created_at' => 'immutable_datetime',
            'updated_at' => 'immutable_datetime',
            'deleted_at' => 'immutable_datetime',
        ];
    }

    /** @return BelongsTo<Tenant, $this> */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * @param  mixed  $value
     * @param  string|null  $field
     */
    // User has no tenant scope, so route binding filters by the current tenant here.
    public function resolveRouteBinding($value, $field = null): ?self
    {
        $context = app(TenantContext::class);

        return $this->resolveRouteBindingQuery($this->newQuery(), $value, $field)
            ->when($context->has(), fn (Builder $query): Builder => $query->where('tenant_id', $context->id()))
            ->first();
    }
}
