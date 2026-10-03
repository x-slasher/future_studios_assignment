<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\Admin;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\CustomerExportController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\PasswordController;
use App\Http\Controllers\Api\V1\PlanController;
use App\Http\Controllers\Api\V1\SubscriptionController;
use App\Http\Controllers\Api\V1\TenantController;
use App\Http\Controllers\Api\V1\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::middleware('throttle:auth')->prefix('auth')->group(function (): void {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('forgot-password', [PasswordController::class, 'forgot']);
        Route::post('reset-password', [PasswordController::class, 'reset']);
    });

    Route::middleware('throttle:public')->get('plans', [PlanController::class, 'index']);

    Route::middleware(['auth:sanctum', 'tenant.context:optional', 'throttle:api'])->prefix('auth')->group(function (): void {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });

    Route::middleware(['auth:sanctum', 'tenant.context', 'tenant.active', 'throttle:api'])->group(function (): void {
        Route::get('tenant', [TenantController::class, 'show']);
        Route::patch('tenant', [TenantController::class, 'update']);

        Route::get('subscription', [SubscriptionController::class, 'show']);
        Route::post('subscription/change-plan', [SubscriptionController::class, 'changePlan']);
        Route::post('subscription/renew', [SubscriptionController::class, 'renew']);
        Route::post('subscription/cancel', [SubscriptionController::class, 'cancel']);

        Route::get('dashboard', [DashboardController::class, 'show']);

        Route::middleware('subscription.writable')->group(function (): void {
            Route::get('users', [UserController::class, 'index']);
            Route::post('users', [UserController::class, 'store']);
            Route::get('users/{user}', [UserController::class, 'show']);
            Route::patch('users/{user}', [UserController::class, 'update']);
            Route::delete('users/{user}', [UserController::class, 'destroy']);

            Route::middleware('feature:customer_export')->group(function (): void {
                Route::post('customers/exports', [CustomerExportController::class, 'store'])->middleware('throttle:exports');
                Route::get('customers/exports/{export}', [CustomerExportController::class, 'show']);
                Route::get('customers/exports/{export}/download', [CustomerExportController::class, 'download'])
                    ->name('customers.exports.download');
            });

            Route::get('customers', [CustomerController::class, 'index']);
            Route::post('customers', [CustomerController::class, 'store']);
            Route::get('customers/{customer}', [CustomerController::class, 'show']);
            Route::patch('customers/{customer}', [CustomerController::class, 'update']);
            Route::delete('customers/{customer}', [CustomerController::class, 'destroy']);
        });
    });

    Route::middleware(['auth:sanctum', 'platform.admin', 'throttle:api'])->prefix('admin')->group(function (): void {
        Route::get('plans', [Admin\PlanController::class, 'index']);
        Route::post('plans', [Admin\PlanController::class, 'store']);
        Route::patch('plans/{plan}', [Admin\PlanController::class, 'update']);

        Route::get('tenants', [Admin\TenantController::class, 'index']);
        Route::get('tenants/{tenant}', [Admin\TenantController::class, 'show']);
        Route::post('tenants/{tenant}/suspend', [Admin\TenantController::class, 'suspend']);
        Route::post('tenants/{tenant}/reactivate', [Admin\TenantController::class, 'reactivate']);

        Route::get('dashboard', [Admin\DashboardController::class, 'show']);
    });
});
