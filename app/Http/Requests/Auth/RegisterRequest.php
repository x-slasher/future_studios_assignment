<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use App\DTOs\RegisterTenantData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'company_name' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->whereNull('deleted_at')],
            'password' => ['required', 'string', 'confirmed', Password::min(8)->max(72)],
            'plan_code' => ['nullable', 'string', Rule::exists('plans', 'code')->where('is_active', true)],
        ];
    }

    /** @return array<string, array<string, mixed>> */
    public function bodyParameters(): array
    {
        return [
            'company_name' => ['description' => 'The company (tenant) name.', 'example' => 'Rahim Traders'],
            'name' => ['description' => 'The owner full name.', 'example' => 'Rahim Uddin'],
            'email' => ['description' => 'Owner email. Unique across the platform among non-deleted users.', 'example' => 'rahim@rahimtraders.test'],
            'password' => ['description' => 'At least 8 characters.', 'example' => 'Secret123'],
            'password_confirmation' => ['description' => 'Must match password.', 'example' => 'Secret123'],
            'plan_code' => ['description' => 'An active plan code: free, starter, or pro. Defaults to starter. A paid plan starts a 14-day trial.', 'example' => 'starter'],
        ];
    }

    public function toDto(): RegisterTenantData
    {
        return new RegisterTenantData(
            companyName: $this->validated('company_name'),
            name: $this->validated('name'),
            email: $this->validated('email'),
            password: $this->validated('password'),
            planCode: $this->validated('plan_code'),
        );
    }
}
