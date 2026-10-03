<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\DTOs\CustomerFilter;
use App\Models\Customer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\LazyCollection;

interface CustomerRepositoryInterface
{
    /** @return LengthAwarePaginator<int, Customer> */
    public function paginate(CustomerFilter $filter): LengthAwarePaginator;

    /** @return LazyCollection<int, Customer> */
    public function lazyForExport(CustomerFilter $filter): LazyCollection;

    /** @param array<string, mixed> $attributes */
    public function create(array $attributes): Customer;

    /** @param array<string, mixed> $attributes */
    public function update(Customer $customer, array $attributes): Customer;

    public function delete(Customer $customer): void;
}
