<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SubscriptionStatus;
use App\Support\Concerns\HasPublicUlid;
use App\Support\Tenancy\BelongsToTenant;
use Carbon\CarbonImmutable;
use Database\Factories\SubscriptionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use BelongsToTenant;

    /** @use HasFactory<SubscriptionFactory> */
    use HasFactory;

    use HasPublicUlid;

    /** @var list<string> */
    protected $fillable = [
        'plan_id',
        'status',
        'trial_ends_at',
        'current_period_start',
        'current_period_end',
        'cancelled_at',
        'ends_at',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'status' => SubscriptionStatus::class,
            'trial_ends_at' => 'immutable_datetime',
            'current_period_start' => 'immutable_datetime',
            'current_period_end' => 'immutable_datetime',
            'cancelled_at' => 'immutable_datetime',
            'ends_at' => 'immutable_datetime',
            'created_at' => 'immutable_datetime',
            'updated_at' => 'immutable_datetime',
        ];
    }

    /** @return BelongsTo<Plan, $this> */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function isUsable(CarbonImmutable $now): bool
    {
        return match ($this->status) {
            SubscriptionStatus::Trialing => $this->trial_ends_at?->greaterThan($now) ?? false,
            SubscriptionStatus::Active, SubscriptionStatus::PastDue => true,
            SubscriptionStatus::Cancelled => $this->ends_at?->greaterThan($now) ?? false,
            SubscriptionStatus::Expired => false,
        };
    }
}
