<?php

declare(strict_types=1);

namespace App\Services;

use App\Contracts\FeatureGate;
use App\DTOs\CreateCustomerData;
use App\DTOs\CustomerFilter;
use App\DTOs\UpdateCustomerData;
use App\Enums\FeatureKey;
use App\Events\CustomerCreated;
use App\Events\CustomerDeleted;
use App\Events\CustomerUpdated;
use App\Exceptions\DuplicateEmail;
use App\Models\Customer;
use App\Models\User;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use App\Repositories\Contracts\TenantRepositoryInterface;
use App\Repositories\Contracts\UsageRepositoryInterface;
use App\Support\Tenancy\TenantContext;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;

final class CustomerService
{
    public function __construct(
        private readonly CustomerRepositoryInterface $customers,
        private readonly TenantRepositoryInterface $tenants,
        private readonly UsageRepositoryInterface $usage,
        private readonly FeatureGate $features,
        private readonly TenantContext $context,
    ) {}

    /** @return LengthAwarePaginator<int, Customer> */
    public function list(CustomerFilter $filter): LengthAwarePaginator
    {
        return $this->customers->paginate($filter);
    }

    public function create(CreateCustomerData $data, User $actor): Customer
    {
        return DB::transaction(function () use ($data, $actor): Customer {
            // Lock the tenant row so two parallel creates cannot both pass the limit check.
            $tenant = $this->tenants->findLocked($this->context->id());

            $this->features->ensureWithinLimit($tenant, FeatureKey::MaxCustomers, $this->usage->customerCount());

            try {
                $customer = $this->customers->create([
                    'name' => $data->name,
                    'email' => $data->email,
                    'phone' => $data->phone,
                    'company_name' => $data->companyName,
                    'status' => $data->status,
                    'created_by_user_id' => $actor->id,
                ]);
            } catch (UniqueConstraintViolationException) {
                throw new DuplicateEmail;
            }

            CustomerCreated::dispatch($customer->tenant_id, $customer->id);

            return $customer->setRelation('createdBy', $actor);
        });
    }

    public function update(Customer $customer, UpdateCustomerData $data): Customer
    {
        try {
            $customer = $this->customers->update($customer, $this->changes($data));
        } catch (UniqueConstraintViolationException) {
            throw new DuplicateEmail;
        }

        CustomerUpdated::dispatch($customer->tenant_id, $customer->id);

        return $customer;
    }

    public function delete(Customer $customer): void
    {
        $this->customers->delete($customer);

        CustomerDeleted::dispatch($customer->tenant_id, $customer->id);
    }

    /** @return array<string, mixed> */
    private function changes(UpdateCustomerData $data): array
    {
        $all = [
            'name' => $data->name,
            'email' => $data->email,
            'phone' => $data->phone,
            'company_name' => $data->companyName,
            'status' => $data->status,
        ];

        return array_intersect_key($all, array_flip($data->provided));
    }
}
