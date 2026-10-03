<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\Concerns\HasPublicUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasPublicUlid;

    /** @var list<string> */
    protected $fillable = [
        'code',
        'name',
        'price_cents',
        'currency',
        'is_active',
        'sort_order',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'price_cents' => 'integer',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'created_at' => 'immutable_datetime',
            'updated_at' => 'immutable_datetime',
        ];
    }

    /** @return HasMany<PlanFeature, $this> */
    public function features(): HasMany
    {
        return $this->hasMany(PlanFeature::class)->orderBy('id');
    }

    public function isFree(): bool
    {
        return $this->price_cents === 0;
    }
}
