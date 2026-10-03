<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ExportStatus;
use App\Support\Concerns\HasPublicUlid;
use App\Support\Tenancy\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerExport extends Model
{
    use BelongsToTenant;
    use HasPublicUlid;

    /** @var list<string> */
    protected $fillable = [
        'requested_by_user_id',
        'status',
        'filters',
        'file_path',
        'row_count',
        'error_message',
        'completed_at',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'status' => ExportStatus::class,
            'filters' => 'array',
            'row_count' => 'integer',
            'completed_at' => 'immutable_datetime',
            'created_at' => 'immutable_datetime',
            'updated_at' => 'immutable_datetime',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by_user_id');
    }
}
