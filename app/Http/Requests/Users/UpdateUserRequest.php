<?php

declare(strict_types=1);

namespace App\Http\Requests\Users;

use App\DTOs\UpdateUserData;
use App\Enums\TenantRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'role' => ['sometimes', Rule::enum(TenantRole::class)],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    /** @return array<string, array<string, mixed>> */
    public function bodyParameters(): array
    {
        return [
            'name' => ['description' => 'Full name.', 'example' => 'Karim Hossain'],
            'role' => ['description' => 'owner, admin, or member. Only an owner can assign owner.', 'example' => 'admin'],
            'is_active' => ['description' => 'false deactivates the user and revokes all their tokens.', 'example' => true],
        ];
    }

    public function toDto(): UpdateUserData
    {
        $isActive = $this->validated('is_active');

        return new UpdateUserData(
            name: $this->validated('name'),
            role: TenantRole::tryFrom((string) $this->validated('role')),
            isActive: $isActive === null ? null : (bool) $isActive,
        );
    }
}
