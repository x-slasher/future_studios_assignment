<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\DTOs\TenantFilter;
use App\Enums\TenantStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListTenantsRequest extends FormRequest
{
    private const array SORTS = ['-created_at', 'name'];

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
            'filter' => ['sometimes', 'array:status,plan,search'],
            'filter.status' => ['sometimes', Rule::enum(TenantStatus::class)],
            'filter.plan' => ['sometimes', 'string', Rule::exists('plans', 'code')],
            'filter.search' => ['sometimes', 'string', 'max:100'],
        ];
    }

    /** @return array<string, array<string, mixed>> */
    public function queryParameters(): array
    {
        return [
            'page' => ['description' => 'Page number, from 1.', 'example' => 1],
            'per_page' => ['description' => 'Items per page, 1 to 100. Default 15.', 'example' => 15],
            'sort' => ['description' => '-created_at (default) or name.', 'example' => 'name'],
            'filter.status' => ['description' => 'active or suspended.', 'example' => 'active'],
            'filter.plan' => ['description' => 'A plan code.', 'example' => 'pro'],
            'filter.search' => ['description' => 'Prefix match on company name.', 'example' => 'Acme'],
        ];
    }

    public function toFilter(): TenantFilter
    {
        $sort = $this->validated('sort', self::SORTS[0]);

        return new TenantFilter(
            status: TenantStatus::tryFrom((string) $this->validated('filter.status')),
            planCode: $this->validated('filter.plan'),
            search: $this->validated('filter.search'),
            sortField: ltrim($sort, '-'),
            sortDirection: str_starts_with($sort, '-') ? 'desc' : 'asc',
            perPage: (int) $this->validated('per_page', 15),
        );
    }
}
