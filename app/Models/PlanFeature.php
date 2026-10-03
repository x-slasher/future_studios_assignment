<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\FeatureKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanFeature extends Model
{
    /** @var list<string> */
    protected $fillable = [
        'feature_key',
        'is_enabled',
        'limit_value',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'feature_key' => FeatureKey::class,
            'is_enabled' => 'boolean',
            'limit_value' => 'integer',
            'created_at' => 'immutable_datetime',
            'updated_at' => 'immutable_datetime',
        ];
    }

    /** @return BelongsTo<Plan, $this> */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
}
