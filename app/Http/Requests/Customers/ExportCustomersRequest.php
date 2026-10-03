<?php

declare(strict_types=1);

namespace App\Http\Requests\Customers;

class ExportCustomersRequest extends ListCustomersRequest
{
    /** @return array<string, mixed> */
    public function rules(): array
    {
        return array_filter(parent::rules(), fn (string $key): bool => str_starts_with($key, 'filter'), ARRAY_FILTER_USE_KEY);
    }

    /** @return array<string, array<string, mixed>> */
    public function bodyParameters(): array
    {
        return array_filter(parent::queryParameters(), fn (string $key): bool => str_starts_with($key, 'filter'), ARRAY_FILTER_USE_KEY);
    }

    /** @return array<string, array<string, mixed>> */
    public function queryParameters(): array
    {
        return [];
    }
}
