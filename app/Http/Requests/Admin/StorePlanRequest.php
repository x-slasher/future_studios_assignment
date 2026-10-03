<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin;

use App\DTOs\PlanData;
use App\Enums\FeatureKey;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('plans', 'code')],
            'name' => ['required', 'string', 'max:100'],
            'price_cents' => ['required', 'integer', 'min:0', 'max:4294967295'],
            'currency' => ['required', 'string', 'size:3', 'uppercase'],
            'is_active' => ['sometimes', 'boolean'],
            'sort_order' => ['sometimes', 'integer', 'min:0', 'max:65535'],
            ...$this->featureRules('required'),
        ];
    }

    /** @return array<string, array<string, mixed>> */
    public function bodyParameters(): array
    {
        return [
            'code' => ['description' => 'Unique plan code used in API requests. Letters, numbers, dashes, underscores.', 'example' => 'business'],
            'name' => ['description' => 'Display name.', 'example' => 'Business'],
            'price_cents' => ['description' => 'Monthly price in cents. 0 makes it a free plan.', 'example' => 9900],
            'currency' => ['description' => 'ISO 4217 code, uppercase.', 'example' => 'USD'],
            'is_active' => ['description' => 'Inactive plans are hidden and cannot be chosen. Default true.', 'example' => true],
            'sort_order' => ['description' => 'Display order. Default 0.', 'example' => 4],
            'features' => ['description' => 'The plan features. Replaces all existing features of the plan.', 'example' => [['key' => 'max_users', 'enabled' => true, 'limit' => 50], ['key' => 'customer_export', 'enabled' => true, 'limit' => null]]],
            'features[].key' => ['description' => 'max_users, max_customers, api_rate_per_minute, customer_export, or analytics_trends.', 'example' => 'max_users'],
            'features[].enabled' => ['description' => 'Whether the feature is on.', 'example' => true],
            'features[].limit' => ['description' => 'For quantity features. null means unlimited. Ignored for on/off features.', 'example' => 50],
        ];
    }

    public function toDto(): PlanData
    {
        $validated = $this->validated();

        return new PlanData(
            code: $validated['code'] ?? null,
            name: $validated['name'] ?? null,
            priceCents: $validated['price_cents'] ?? null,
            currency: $validated['currency'] ?? null,
            isActive: $validated['is_active'] ?? null,
            sortOrder: $validated['sort_order'] ?? null,
            features: isset($validated['features']) ? $this->features($validated['features']) : null,
        );
    }

    /** @return array<string, mixed> */
    protected function featureRules(string $presence): array
    {
        return [
            'features' => [$presence, 'array', 'min:1'],
            'features.*.key' => ['required', 'distinct', Rule::enum(FeatureKey::class)],
            'features.*.enabled' => ['required', 'boolean'],
            'features.*.limit' => ['nullable', 'integer', 'min:0', 'max:4294967295'],
        ];
    }

    /**
     * @param  list<array{key: string, enabled: bool, limit?: int|null}>  $features
     * @return list<array{key: FeatureKey, enabled: bool, limit: int|null}>
     */
    private function features(array $features): array
    {
        return array_map(fn (array $feature): array => [
            'key' => FeatureKey::from($feature['key']),
            'enabled' => (bool) $feature['enabled'],
            'limit' => isset($feature['limit']) ? (int) $feature['limit'] : null,
        ], $features);
    }
}
