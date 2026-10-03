<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SubscriptionEventType;
use App\Enums\SubscriptionStatus;
use App\Support\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionEvent extends Model
{
    use BelongsToTenant;

    public $timestamps = false;

    /** @var list<string> */
    protected $fillable = [
        'subscription_id',
        'type',
        'from_status',
        'to_status',
        'from_plan_id',
        'to_plan_id',
        'occurred_at',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'type' => SubscriptionEventType::class,
            'from_status' => SubscriptionStatus::class,
            'to_status' => SubscriptionStatus::class,
            'occurred_at' => 'immutable_datetime',
        ];
    }

    /** @return BelongsTo<Subscription, $this> */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }
}
