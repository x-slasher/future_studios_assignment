<?php

declare(strict_types=1);

namespace App\Http\Requests\Tenant;

use App\DTOs\UpdateTenantData;
use Illuminate\Foundation\Http\FormRequest;

class UpdateTenantRequest extends FormRequest
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
        ];
    }

    /** @return array<string, array<string, mixed>> */
    public function bodyParameters(): array
    {
        return [
            'name' => ['description' => 'The new company name.', 'example' => 'Acme Limited'],
        ];
    }

    public function toDto(): UpdateTenantData
    {
        return new UpdateTenantData($this->validated('name'));
    }
}
