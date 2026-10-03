<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\RegisterTenantData;
use App\DTOs\RegistrationResult;
use App\DTOs\TenantFilter;
use App\DTOs\UpdateTenantData;
use App\Enums\TenantRole;
use App\Enums\TenantStatus;
use App\Events\TenantRegistered;
use App\Events\TenantStatusChanged;
use App\Events\TenantUpdated;
use App\Exceptions\DuplicateEmail;
use App\Models\Tenant;
use App\Models\User;
use App\Repositories\Contracts\PlanRepositoryInterface;
use App\Repositories\Contracts\TenantRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Support\Tenancy\TenantContext;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;

final class TenantService
{
    public function __construct(
        private readonly TenantRepositoryInterface $tenants,
        private readonly UserRepositoryInterface $users,
        private readonly PlanRepositoryInterface $plans,
        private readonly SubscriptionService $subscriptions,
        private readonly TenantContext $context,
    ) {}

    public function register(RegisterTenantData $data): RegistrationResult
    {
        return DB::transaction(function () use ($data): RegistrationResult {
            $plan = $this->plans->findActiveByCode($data->planCode ?? (string) config('billing.default_plan'));
            $tenant = $this->tenants->create(['name' => $data->companyName, 'status' => TenantStatus::Active]);
            $owner = $this->createOwner($tenant, $data);
            $subscription = $this->subscriptions->startTrial($tenant, $plan)->setRelation('plan', $plan);

            TenantRegistered::dispatch($tenant->id, $tenant->name, $owner->name, $owner->email);

            return new RegistrationResult($tenant, $owner, $subscription, $owner->createToken('api')->plainTextToken);
        });
    }

    public function current(): Tenant
    {
        return $this->context->tenant();
    }

    public function update(UpdateTenantData $data): Tenant
    {
        $tenant = $this->tenants->update($this->context->tenant(), ['name' => $data->name]);

        TenantUpdated::dispatch($tenant->id);

        return $tenant;
    }

    /** @return LengthAwarePaginator<int, Tenant> */
    public function adminList(TenantFilter $filter): LengthAwarePaginator
    {
        return $this->tenants->paginateAcrossTenants($filter);
    }

    /** @return array{tenant: Tenant, usage: array<string, array{used: int, limit: int|null, over_limit: bool}>} */
    public function adminShow(Tenant $tenant): array
    {
        return $this->context->run($tenant, fn (): array => [
            'tenant' => $tenant->setRelation('subscription', $this->subscriptions->current()),
            'usage' => $this->subscriptions->usage(),
        ]);
    }

    public function suspend(Tenant $tenant): Tenant
    {
        return DB::transaction(function () use ($tenant): Tenant {
            $tenant = $this->tenants->update($tenant, [
                'status' => TenantStatus::Suspended,
                'suspended_at' => CarbonImmutable::now(),
            ]);
            $this->users->revokeTokensForTenant($tenant->id);

            TenantStatusChanged::dispatch($tenant->id);

            return $tenant;
        });
    }

    public function reactivate(Tenant $tenant): Tenant
    {
        $tenant = $this->tenants->update($tenant, ['status' => TenantStatus::Active, 'suspended_at' => null]);

        TenantStatusChanged::dispatch($tenant->id);

        return $tenant;
    }

    private function createOwner(Tenant $tenant, RegisterTenantData $data): User
    {
        try {
            $owner = $this->users->create([
                'tenant_id' => $tenant->id,
                'name' => $data->name,
                'email' => $data->email,
                'password' => $data->password,
                'is_active' => true,
            ]);
        } catch (UniqueConstraintViolationException) {
            throw new DuplicateEmail;
        }

        return $this->context->run($tenant, function () use ($owner): User {
            $owner->assignRole(TenantRole::Owner->value);

            return $this->users->loadProfile($owner);
        });
    }
}
