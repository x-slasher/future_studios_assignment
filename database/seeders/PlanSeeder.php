<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\FeatureKey;
use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->plans() as $sortOrder => $definition) {
            $plan = Plan::query()->updateOrCreate(['code' => $definition['code']], [
                'name' => $definition['name'],
                'price_cents' => $definition['price_cents'],
                'currency' => 'USD',
                'is_active' => true,
                'sort_order' => $sortOrder + 1,
            ]);

            foreach ($definition['features'] as $key => $value) {
                $feature = FeatureKey::from($key);

                $plan->features()->updateOrCreate(['feature_key' => $feature], [
                    'is_enabled' => $feature->isQuantity() ? true : $value,
                    'limit_value' => $feature->isQuantity() ? $value : null,
                ]);
            }
        }
    }

    /** @return list<array{code: string, name: string, price_cents: int, features: array<string, int|bool>}> */
    private function plans(): array
    {
        return [
            ['code' => 'free', 'name' => 'Free', 'price_cents' => 0, 'features' => [
                'max_users' => 2, 'max_customers' => 50, 'api_rate_per_minute' => 60,
                'customer_export' => false, 'analytics_trends' => false,
            ]],
            ['code' => 'starter', 'name' => 'Starter', 'price_cents' => 1900, 'features' => [
                'max_users' => 5, 'max_customers' => 500, 'api_rate_per_minute' => 120,
                'customer_export' => true, 'analytics_trends' => false,
            ]],
            ['code' => 'pro', 'name' => 'Pro', 'price_cents' => 4900, 'features' => [
                'max_users' => 25, 'max_customers' => 5000, 'api_rate_per_minute' => 600,
                'customer_export' => true, 'analytics_trends' => true,
            ]],
        ];
    }
}
