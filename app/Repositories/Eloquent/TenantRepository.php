<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\DTOs\TenantFilter;
use App\Enums\TenantStatus;
use App\Models\Tenant;
use App\Repositories\Contracts\TenantRepositoryInterface;
use App\Support\Tenancy\TenantScope;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;

final class TenantRepository implements TenantRepositoryInterface
{
    private const array SORTABLE = ['created_at', 'name'];

    public function create(array $attributes): Tenant
    {
        return Tenant::query()->create($attributes);
    }

    public function update(Tenant $tenant, array $attributes): Tenant
    {
        $tenant->update($attributes);

        return $tenant;
    }

    public function findById(int $id): Tenant
    {
        return Tenant::query()->findOrFail($id);
    }

    public function findLocked(int $id): Tenant
    {
        return Tenant::query()->lockForUpdate()->findOrFail($id);
    }

    public function paginateAcrossTenants(TenantFilter $filter): LengthAwarePaginator
    {
        $sort = in_array($filter->sortField, self::SORTABLE, true) ? $filter->sortField : 'created_at';

        return Tenant::query()
            ->with([
                'subscription' => fn (HasOne $query): HasOne => $query->withoutGlobalScope(TenantScope::class),
                'subscription.plan.features',
            ])
            ->when($filter->status, fn (Builder $q, TenantStatus $status): Builder => $q->where('status', $status))
            ->when($filter->planCode, fn (Builder $q, string $code): Builder => $q->whereHas(
                'subscription',
                fn (Builder $sub): Builder => $sub->withoutGlobalScope(TenantScope::class)
                    ->whereHas('plan', fn (Builder $plan): Builder => $plan->where('code', $code)),
            ))
            ->when($filter->search, fn (Builder $q, string $term): Builder => $q->where('name', 'like', addcslashes($term, '\\%_').'%'))
            ->orderBy($sort, $filter->sortDirection)
            ->orderBy('id', $filter->sortDirection)
            ->paginate($filter->perPage)
            ->withQueryString();
    }

    public function activeIds(): array
    {
        return Tenant::query()->where('status', TenantStatus::Active)->orderBy('id')->pluck('id')->all();
    }

    public function countsByStatus(): array
    {
        return Tenant::query()
            ->toBase()
            ->select('status')
            ->selectRaw('COUNT(*) AS total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->map(fn (mixed $total): int => (int) $total)
            ->all();
    }

    public function countCreatedSince(CarbonImmutable $since): int
    {
        return Tenant::query()->where('created_at', '>=', $since)->count();
    }
}
