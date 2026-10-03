<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

class UpdatePlanRequest extends StorePlanRequest
{
    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'code' => ['prohibited'],
            'name' => ['sometimes', 'string', 'max:100'],
            'price_cents' => ['sometimes', 'integer', 'min:0', 'max:4294967295'],
            'currency' => ['sometimes', 'string', 'size:3', 'uppercase'],
            'is_active' => ['sometimes', 'boolean'],
            'sort_order' => ['sometimes', 'integer', 'min:0', 'max:65535'],
            ...$this->featureRules('sometimes'),
        ];
    }

    /** @return array<string, array<string, mixed>> */
    public function bodyParameters(): array
    {
        $parameters = parent::bodyParameters();
        unset($parameters['code']);

        return $parameters;
    }
}
