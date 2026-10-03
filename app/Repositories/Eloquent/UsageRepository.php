<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Customer;
use App\Models\DailyUsageSnapshot;
use App\Models\User;
use App\Repositories\Contracts\UsageRepositoryInterface;
use App\Support\Tenancy\TenantContext;
use App\Support\Tenancy\TenantScope;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

final class UsageRepository implements UsageRepositoryInterface
{
    private const int UPSERT_CHUNK = 500;

    public function __construct(private readonly TenantContext $context) {}

    public function userCount(): int
    {
        return User::query()->where('tenant_id', $this->context->id())->count();
    }

    public function customerCount(): int
    {
        return Customer::query()->count();
    }

    public function customerCountsByStatus(): array
    {
        return $this->countsBy(Customer::query(), 'status')->all();
    }

    public function newCustomersSince(CarbonImmutable $since): int
    {
        return Customer::query()->where('created_at', '>=', $since)->count();
    }

    public function trend(int $days): array
    {
        return DailyUsageSnapshot::query()
            ->where('snapshot_date', '>=', CarbonImmutable::today()->subDays($days - 1))
            ->orderBy('snapshot_date')
            ->get(['snapshot_date', 'users_count', 'customers_count'])
            ->map(fn (DailyUsageSnapshot $snapshot): array => [
                'date' => $snapshot->snapshot_date->toDateString(),
                'users' => $snapshot->users_count,
                'customers' => $snapshot->customers_count,
            ])
            ->all();
    }

    public function userCountsAcrossTenants(): array
    {
        return $this->countsBy(User::query()->whereNotNull('tenant_id'), 'tenant_id')->all();
    }

    public function customerCountsAcrossTenants(): array
    {
        return $this->countsBy(Customer::withoutGlobalScope(TenantScope::class), 'tenant_id')->all();
    }

    public function upsertSnapshotsAcrossTenants(array $rows): void
    {
        foreach (array_chunk($rows, self::UPSERT_CHUNK) as $chunk) {
            // Eloquent applies global scopes to upsert, and no tenant is set here.
            DailyUsageSnapshot::withoutGlobalScope(TenantScope::class)->upsert(
                $chunk,
                uniqueBy: ['tenant_id', 'snapshot_date'],
                update: ['users_count', 'customers_count', 'updated_at'],
            );
        }
    }

    /**
     * @param  Builder<covariant \Illuminate\Database\Eloquent\Model>  $query
     * @return Collection<array-key, int>
     */
    private function countsBy(Builder $query, string $column): Collection
    {
        return $query->toBase()
            ->select($column)
            ->selectRaw('COUNT(*) AS total')
            ->groupBy($column)
            ->pluck('total', $column)
            ->map(fn (mixed $total): int => (int) $total);
    }
}
