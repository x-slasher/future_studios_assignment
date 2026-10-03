<?php

declare(strict_types=1);

use App\Repositories\Contracts;
use App\Repositories\Eloquent;

dataset('repositories', [
    'tenant' => [Contracts\TenantRepositoryInterface::class, Eloquent\TenantRepository::class],
    'user' => [Contracts\UserRepositoryInterface::class, Eloquent\UserRepository::class],
    'customer' => [Contracts\CustomerRepositoryInterface::class, Eloquent\CustomerRepository::class],
    'customer export' => [Contracts\CustomerExportRepositoryInterface::class, Eloquent\CustomerExportRepository::class],
    'plan' => [Contracts\PlanRepositoryInterface::class, Eloquent\PlanRepository::class],
    'subscription' => [Contracts\SubscriptionRepositoryInterface::class, Eloquent\SubscriptionRepository::class],
    'usage' => [Contracts\UsageRepositoryInterface::class, Eloquent\UsageRepository::class],
]);

it('resolves each repository interface to its Eloquent class', function (string $interface, string $class): void {
    expect(app($interface))->toBeInstanceOf($class);
})->with('repositories');

it('gives each implementation exactly the public methods of its interface', function (string $interface, string $class): void {
    $publicMethods = fn (string $type): array => collect((new ReflectionClass($type))->getMethods(ReflectionMethod::IS_PUBLIC))
        ->reject(fn (ReflectionMethod $method): bool => $method->isConstructor())
        ->map(fn (ReflectionMethod $method): string => $method->getName())
        ->sort()
        ->values()
        ->all();

    expect((new ReflectionClass($class))->isFinal())->toBeTrue()
        ->and($publicMethods($class))->toBe($publicMethods($interface));
})->with('repositories');

it('covers every interface in app/Repositories/Contracts', function (): void {
    $files = glob(app_path('Repositories/Contracts/*RepositoryInterface.php'));

    expect($files)->toHaveCount(7);
});
