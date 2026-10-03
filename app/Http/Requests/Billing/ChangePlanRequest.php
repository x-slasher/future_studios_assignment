<?php

declare(strict_types=1);

namespace App\Http\Requests\Billing;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangePlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'plan_code' => ['required', 'string', Rule::exists('plans', 'code')->where('is_active', true)],
        ];
    }

    /** @return array<string, array<string, mixed>> */
    public function bodyParameters(): array
    {
        return [
            'plan_code' => ['description' => 'An active plan code. Moving to free makes the subscription active with no period.', 'example' => 'pro'],
        ];
    }

    public function planCode(): string
    {
        return $this->validated('plan_code');
    }
}
