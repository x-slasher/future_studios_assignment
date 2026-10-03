<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\PlanData;
use App\Enums\FeatureKey;
use App\Events\PlanChanged;
use App\Http\Resources\PlanResource;
use App\Models\Plan;
use App\Repositories\Contracts\PlanRepositoryInterface;
use App\Support\Cache\CacheScope;
use App\Support\Cache\VersionedCache;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

final class PlanService
{
    private const int ACTIVE_LIST_TTL_SECONDS = 3600;

    public function __construct(
        private readonly PlanRepositoryInterface $plans,
        private readonly VersionedCache $cache,
    ) {}

    /** @return list<array<string, mixed>> */
    public function activeForDisplay(): array
    {
        return $this->cache->remember(
            CacheScope::platform(),
            'plans:active',
            self::ACTIVE_LIST_TTL_SECONDS,
            fn (): array => PlanResource::collection($this->plans->activeWithFeatures())->resolve(),
        );
    }

    /** @return Collection<int, Plan> */
    public function all(): Collection
    {
        return $this->plans->allWithFeatures();
    }

    public function create(PlanData $data): Plan
    {
        return DB::transaction(function () use ($data): Plan {
            $plan = $this->plans->create($this->attributes($data));
            $this->plans->syncFeatures($plan, $this->featureRows($data->features ?? []));

            PlanChanged::dispatch($plan->id);

            return $plan;
        });
    }

    public function update(Plan $plan, PlanData $data): Plan
    {
        return DB::transaction(function () use ($plan, $data): Plan {
            $plan = $this->plans->update($plan, $this->attributes($data));

            if ($data->features !== null) {
                $this->plans->syncFeatures($plan, $this->featureRows($data->features));
            }

            PlanChanged::dispatch($plan->id);

            return $plan;
        });
    }

    /** @return array<string, mixed> */
    private function attributes(PlanData $data): array
    {
        return array_filter([
            'code' => $data->code,
            'name' => $data->name,
            'price_cents' => $data->priceCents,
            'currency' => $data->currency,
            'is_active' => $data->isActive,
            'sort_order' => $data->sortOrder,
        ], fn (mixed $value): bool => $value !== null);
    }

    /**
     * @param  list<array{key: FeatureKey, enabled: bool, limit: int|null}>  $features
     * @return list<array{feature_key: FeatureKey, is_enabled: bool, limit_value: int|null}>
     */
    private function featureRows(array $features): array
    {
        return array_map(fn (array $feature): array => [
            'feature_key' => $feature['key'],
            'is_enabled' => $feature['enabled'],
            'limit_value' => $feature['key']->isQuantity() ? $feature['limit'] : null,
        ], $features);
    }
}
