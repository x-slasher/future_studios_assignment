<?php

declare(strict_types=1);

namespace App\Providers;

use App\Repositories\Contracts\CustomerExportRepositoryInterface;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use App\Repositories\Contracts\PlanRepositoryInterface;
use App\Repositories\Contracts\SubscriptionRepositoryInterface;
use App\Repositories\Contracts\TenantRepositoryInterface;
use App\Repositories\Contracts\UsageRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\CustomerExportRepository;
use App\Repositories\Eloquent\CustomerRepository;
use App\Repositories\Eloquent\PlanRepository;
use App\Repositories\Eloquent\SubscriptionRepository;
use App\Repositories\Eloquent\TenantRepository;
use App\Repositories\Eloquent\UsageRepository;
use App\Repositories\Eloquent\UserRepository;
use Illuminate\Support\ServiceProvider;

final class RepositoryServiceProvider extends ServiceProvider
{
    /** @var array<class-string, class-string> */
    public array $bindings = [
        TenantRepositoryInterface::class => TenantRepository::class,
        UserRepositoryInterface::class => UserRepository::class,
        CustomerRepositoryInterface::class => CustomerRepository::class,
        CustomerExportRepositoryInterface::class => CustomerExportRepository::class,
        PlanRepositoryInterface::class => PlanRepository::class,
        SubscriptionRepositoryInterface::class => SubscriptionRepository::class,
        UsageRepositoryInterface::class => UsageRepository::class,
    ];
}
