<?php

declare(strict_types=1);

namespace App\Http\Requests\Users;

use App\DTOs\CreateUserData;
use App\Enums\TenantRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->whereNull('deleted_at')],
            'role' => ['required', Rule::enum(TenantRole::class)],
        ];
    }

    /** @return array<string, array<string, mixed>> */
    public function bodyParameters(): array
    {
        return [
            'name' => ['description' => 'Full name.', 'example' => 'Karim Hossain'],
            'email' => ['description' => 'Unique across the platform among non-deleted users. A set-password email is sent here.', 'example' => 'karim@acme.test'],
            'role' => ['description' => 'owner, admin, or member. Only an owner can assign owner.', 'example' => 'member'],
        ];
    }

    public function toDto(): CreateUserData
    {
        return new CreateUserData(
            name: $this->validated('name'),
            email: $this->validated('email'),
            role: TenantRole::from($this->validated('role')),
        );
    }
}
