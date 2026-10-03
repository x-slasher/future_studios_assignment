<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use App\DTOs\ResetPasswordData;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'token' => ['required', 'string'],
            'password' => ['required', 'string', 'confirmed', Password::min(8)->max(72)],
        ];
    }

    /** @return array<string, array<string, mixed>> */
    public function bodyParameters(): array
    {
        return [
            'email' => ['description' => 'Account email.', 'example' => 'owner@acme.test'],
            'token' => ['description' => 'The token from the set-password email.', 'example' => 'token-from-the-email'],
            'password' => ['description' => 'The new password. At least 8 characters.', 'example' => 'NewSecret123'],
            'password_confirmation' => ['description' => 'Must match password.', 'example' => 'NewSecret123'],
        ];
    }

    public function toDto(): ResetPasswordData
    {
        return new ResetPasswordData(
            email: $this->validated('email'),
            token: $this->validated('token'),
            password: $this->validated('password'),
        );
    }
}
