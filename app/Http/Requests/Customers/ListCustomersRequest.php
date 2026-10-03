<?php

declare(strict_types=1);

namespace App\Http\Requests\Customers;

use App\DTOs\CustomerFilter;
use App\Enums\CustomerStatus;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListCustomersRequest extends FormRequest
{
    private const array SORTS = ['-created_at', 'created_at', 'name', '-name'];

    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'page' => ['sometimes', 'integer', 'min:1', 'max:1000000'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'sort' => ['sometimes', 'string', Rule::in(self::SORTS)],
            'filter' => ['sometimes', 'array:status,search,created_from,created_to'],
            'filter.status' => ['sometimes', Rule::enum(CustomerStatus::class)],
            'filter.search' => ['sometimes', 'string', 'max:100'],
            'filter.created_from' => ['sometimes', 'date_format:Y-m-d'],
            'filter.created_to' => ['sometimes', 'date_format:Y-m-d', 'after_or_equal:filter.created_from'],
        ];
    }

    /** @return array<string, array<string, mixed>> */
    public function queryParameters(): array
    {
        return [
            'page' => ['description' => 'Page number, from 1.', 'example' => 1],
            'per_page' => ['description' => 'Items per page, 1 to 100. Default 15.', 'example' => 15],
            'sort' => ['description' => '-created_at (default), created_at, name, or -name.', 'example' => '-created_at'],
            'filter.status' => ['description' => 'active or inactive.', 'example' => 'active'],
            'filter.search' => ['description' => 'Prefix match on name or email.', 'example' => 'kar'],
            'filter.created_from' => ['description' => 'Created on or after this date (Y-m-d).', 'example' => '2026-09-01'],
            'filter.created_to' => ['description' => 'Created on or before this date (Y-m-d).', 'example' => '2026-09-30'],
        ];
    }

    public function toFilter(): CustomerFilter
    {
        $sort = $this->validated('sort', self::SORTS[0]);

        return new CustomerFilter(
            status: CustomerStatus::tryFrom((string) $this->validated('filter.status')),
            search: $this->validated('filter.search'),
            createdFrom: $this->validatedDate('filter.created_from'),
            createdTo: $this->validatedDate('filter.created_to'),
            sortField: ltrim($sort, '-'),
            sortDirection: str_starts_with($sort, '-') ? 'desc' : 'asc',
            perPage: (int) $this->validated('per_page', 15),
        );
    }

    private function validatedDate(string $key): ?CarbonImmutable
    {
        $value = $this->validated($key);

        return $value === null ? null : CarbonImmutable::createFromFormat('Y-m-d', $value);
    }
}
