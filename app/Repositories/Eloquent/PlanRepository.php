<?php

declare(strict_types=1);

namespace App\Repositories\Eloquent;

use App\Models\Plan;
use App\Models\PlanFeature;
use App\Repositories\Contracts\PlanRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

final class PlanRepository implements PlanRepositoryInterface
{
    public function activeWithFeatures(): Collection
    {
        return Plan::query()
            ->where('is_active', true)
            ->with('features')
            ->orderBy('sort_order')
            ->get();
    }

    public function allWithFeatures(): Collection
    {
        return Plan::query()
            ->with('features')
            ->orderBy('sort_order')
            ->get();
    }

    public function findActiveByCode(string $code): Plan
    {
        return Plan::query()
            ->where('code', $code)
            ->where('is_active', true)
            ->with('features')
            ->firstOrFail();
    }

    public function create(array $attributes): Plan
    {
        return Plan::query()->create($attributes);
    }

    public function update(Plan $plan, array $attributes): Plan
    {
        $plan->update($attributes);

        return $plan->loadMissing('features');
    }

    public function syncFeatures(Plan $plan, array $features): void
    {
        $plan->features()->delete();
        $plan->setRelation('features', $plan->features()->createMany($features));
    }

    public function featureMapForTenant(int $tenantId): array
    {
        return PlanFeature::query()
            ->join('subscriptions', 'subscriptions.plan_id', '=', 'plan_features.plan_id')
            ->where('subscriptions.tenant_id', $tenantId)
            ->get(['plan_features.feature_key', 'plan_features.is_enabled', 'plan_features.limit_value'])
            ->mapWithKeys(fn (PlanFeature $feature): array => [
                $feature->feature_key->value => ['enabled' => $feature->is_enabled, 'limit' => $feature->limit_value],
            ])
            ->all();
    }
}
