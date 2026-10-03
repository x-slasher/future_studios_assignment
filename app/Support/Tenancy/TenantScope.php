<?php

declare(strict_types=1);

namespace App\Support\Tenancy;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

final class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        // Throws when no tenant is set: a query without a tenant is a bug.
        $builder->where($model->qualifyColumn('tenant_id'), app(TenantContext::class)->id());
    }
}
