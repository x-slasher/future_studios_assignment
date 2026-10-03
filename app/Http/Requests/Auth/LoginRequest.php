<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use App\DTOs\LoginData;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'password' => ['required', 'string'],
        ];
    }

    /** @return array<string, array<string, mixed>> */
    public function bodyParameters(): array
    {
        return [
            'email' => ['description' => 'Account email.', 'example' => 'owner@acme.test'],
            'password' => ['description' => 'Account password.', 'example' => 'password'],
        ];
    }

    public function toDto(): LoginData
    {
        return new LoginData($this->validated('email'), $this->validated('password'));
    }
}
