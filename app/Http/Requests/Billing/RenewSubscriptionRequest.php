<?php

declare(strict_types=1);

namespace App\Http\Requests\Billing;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RenewSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'plan_code' => ['nullable', 'string', Rule::exists('plans', 'code')->where('is_active', true)],
        ];
    }

    /** @return array<string, array<string, mixed>> */
    public function bodyParameters(): array
    {
        return [
            'plan_code' => ['description' => 'Optional active paid plan code to switch to while renewing. Defaults to the current plan.', 'example' => 'pro'],
        ];
    }

    public function planCode(): ?string
    {
        return $this->validated('plan_code');
    }
}
