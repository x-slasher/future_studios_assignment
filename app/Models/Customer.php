<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CustomerStatus;
use App\Support\Concerns\HasPublicUlid;
use App\Support\Tenancy\BelongsToTenant;
use Database\Factories\CustomerFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use BelongsToTenant;

    /** @use HasFactory<CustomerFactory> */
    use HasFactory;

    use HasPublicUlid;
    use SoftDeletes;

    /** @var list<string> */
    protected $fillable = [
        'created_by_user_id',
        'name',
        'email',
        'phone',
        'company_name',
        'status',
    ];

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'status' => CustomerStatus::class,
            'created_at' => 'immutable_datetime',
            'updated_at' => 'immutable_datetime',
            'deleted_at' => 'immutable_datetime',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * @param  Customer|Builder<Customer>|Relation<Customer, Model, Customer>  $query
     * @param  mixed  $value
     * @param  string|null  $field
     * @return Builder<Customer>|Relation<Customer, Model, Customer>
     */
    public function resolveRouteBindingQuery($query, $value, $field = null): Builder|Relation
    {
        return parent::resolveRouteBindingQuery($query, $value, $field)->with('createdBy:id,ulid,name');
    }
}
