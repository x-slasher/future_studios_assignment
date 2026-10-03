<?php

declare(strict_types=1);

namespace App\Http\Requests\Customers;

use App\DTOs\UpdateCustomerData;
use App\Enums\CustomerStatus;
use App\Models\Customer;
use App\Support\Tenancy\TenantContext;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        /** @var Customer|null $customer */
        $customer = $this->route('customer');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email', 'max:255', Rule::unique('customers', 'email')
                ->where(fn (Builder $query): Builder => $query->where('tenant_id', app(TenantContext::class)->id()))
                ->whereNull('deleted_at')
                ->ignore($customer?->id)],
            'phone' => ['sometimes', 'nullable', 'string', 'max:30'],
            'company_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'status' => ['sometimes', 'required', Rule::enum(CustomerStatus::class)],
        ];
    }

    /** @return array<string, array<string, mixed>> */
    public function bodyParameters(): array
    {
        return [
            'name' => ['description' => 'Customer name.', 'example' => 'Karim Store'],
            'email' => ['description' => 'Unique within your company among non-deleted customers.', 'example' => 'karim@store.test'],
            'phone' => ['description' => 'Phone number. Send null to clear it.', 'example' => '+8801700000000'],
            'company_name' => ['description' => 'Company name. Send null to clear it.', 'example' => 'Karim Ltd'],
            'status' => ['description' => 'active or inactive.', 'example' => 'inactive'],
        ];
    }

    public function toDto(): UpdateCustomerData
    {
        $validated = $this->validated();

        return new UpdateCustomerData(
            name: $validated['name'] ?? null,
            email: $validated['email'] ?? null,
            phone: $validated['phone'] ?? null,
            companyName: $validated['company_name'] ?? null,
            status: isset($validated['status']) ? CustomerStatus::from($validated['status']) : null,
            provided: array_keys($validated),
        );
    }
}
