<?php

declare(strict_types=1);

use App\Exceptions\ApiException;
use App\Exceptions\ApiExceptionRenderer;
use App\Http\Middleware\EnsureFeatureEnabled;
use App\Http\Middleware\EnsurePlatformAdmin;
use App\Http\Middleware\EnsureSubscriptionAllowsWrites;
use App\Http\Middleware\EnsureTenantIsActive;
use App\Http\Middleware\SetTenantContext;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\ThrottleRequestsWithRedis;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    // Listeners are registered in AppServiceProvider; discovery would register them twice.
    ->withEvents(discover: false)
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->throttleWithRedis();
        // API only: unauthenticated requests get 401, not a redirect to a login page.
        $middleware->redirectGuestsTo(null);
        $middleware->alias([
            'tenant.context' => SetTenantContext::class,
            'tenant.active' => EnsureTenantIsActive::class,
            'subscription.writable' => EnsureSubscriptionAllowsWrites::class,
            'feature' => EnsureFeatureEnabled::class,
            'platform.admin' => EnsurePlatformAdmin::class,
        ]);
        // The plan-based rate limiter needs the tenant, so set it before throttling.
        $middleware->prependToPriorityList(before: ThrottleRequestsWithRedis::class, prepend: SetTenantContext::class);
        $middleware->prependToPriorityList(before: ThrottleRequestsWithRedis::class, prepend: EnsureTenantIsActive::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->dontReport(ApiException::class);
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request): bool => $request->is('api/*') || $request->expectsJson(),
        );
        $exceptions->render(
            fn (Throwable $e, Request $request) => $request->is('api/*')
                ? (new ApiExceptionRenderer((bool) config('app.debug')))->render($e)
                : null,
        );
    })->create();
