<?php

declare(strict_types=1);

namespace App\Http\Requests\Customers;

use App\DTOs\CreateCustomerData;
use App\Enums\CustomerStatus;
use App\Support\Tenancy\TenantContext;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('customers', 'email')
                ->where(fn (Builder $query): Builder => $query->where('tenant_id', app(TenantContext::class)->id()))
                ->whereNull('deleted_at')],
            'phone' => ['nullable', 'string', 'max:30'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'status' => ['sometimes', Rule::enum(CustomerStatus::class)],
        ];
    }

    /** @return array<string, array<string, mixed>> */
    public function bodyParameters(): array
    {
        return [
            'name' => ['description' => 'Customer name.', 'example' => 'Karim Store'],
            'email' => ['description' => 'Unique within your company among non-deleted customers.', 'example' => 'karim@store.test'],
            'phone' => ['description' => 'Optional phone number.', 'example' => '+8801700000000'],
            'company_name' => ['description' => 'Optional company name.', 'example' => 'Karim Ltd'],
            'status' => ['description' => 'active or inactive. Defaults to active.', 'example' => 'active'],
        ];
    }

    public function toDto(): CreateCustomerData
    {
        return new CreateCustomerData(
            name: $this->validated('name'),
            email: $this->validated('email'),
            phone: $this->validated('phone'),
            companyName: $this->validated('company_name'),
            status: CustomerStatus::tryFrom((string) $this->validated('status')) ?? CustomerStatus::Active,
        );
    }
}
