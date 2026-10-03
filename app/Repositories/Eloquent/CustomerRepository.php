<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\DTOs\CustomerFilter;
use App\Enums\CustomerStatus;
use App\Models\Customer;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\LazyCollection;

final class CustomerRepository implements CustomerRepositoryInterface
{
    private const array SORTABLE = ['created_at', 'name'];

    public function paginate(CustomerFilter $filter): LengthAwarePaginator
    {
        $sort = in_array($filter->sortField, self::SORTABLE, true) ? $filter->sortField : 'created_at';

        return $this->filtered($filter)
            ->with('createdBy:id,ulid,name')
            ->orderBy($sort, $filter->sortDirection)
            // Tie-breaker, so pages stay stable when values repeat.
            ->orderBy('id', $filter->sortDirection)
            ->paginate($filter->perPage)
            ->withQueryString();
    }

    public function lazyForExport(CustomerFilter $filter): LazyCollection
    {
        return $this->filtered($filter)->lazyById(1000);
    }

    public function create(array $attributes): Customer
    {
        return Customer::query()->create($attributes);
    }

    public function update(Customer $customer, array $attributes): Customer
    {
        $customer->update($attributes);

        return $customer;
    }

    public function delete(Customer $customer): void
    {
        $customer->delete();
    }

    /** @return Builder<Customer> */
    private function filtered(CustomerFilter $filter): Builder
    {
        return Customer::query()
            ->when($filter->status, fn (Builder $q, CustomerStatus $status): Builder => $q->where('status', $status))
            ->when($filter->search, fn (Builder $q, string $term): Builder => $q->where(
                fn (Builder $q): Builder => $q->where('name', 'like', $this->prefix($term))
                    ->orWhere('email', 'like', $this->prefix($term)),
            ))
            ->when($filter->createdFrom, fn (Builder $q, CarbonImmutable $d): Builder => $q->where('created_at', '>=', $d->startOfDay()))
            ->when($filter->createdTo, fn (Builder $q, CarbonImmutable $d): Builder => $q->where('created_at', '<=', $d->endOfDay()));
    }

    private function prefix(string $term): string
    {
        return addcslashes($term, '\\%_').'%';
    }
}
