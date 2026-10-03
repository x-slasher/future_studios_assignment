<?php

declare(strict_types=1);

namespace App\Models;

use App\Support\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;

class DailyUsageSnapshot extends Model
{
    use BelongsToTenant;

    /** @var list<string> */
    protected $fillable = [
        'snapshot_date',
        'users_count',
        'customers_count',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'snapshot_date' => 'immutable_date',
            'users_count' => 'integer',
            'customers_count' => 'integer',
            'created_at' => 'immutable_datetime',
            'updated_at' => 'immutable_datetime',
        ];
    }
}
