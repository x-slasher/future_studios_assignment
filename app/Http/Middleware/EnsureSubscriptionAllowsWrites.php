<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Exceptions\SubscriptionInactive;
use App\Repositories\Contracts\SubscriptionRepositoryInterface;
use Carbon\CarbonImmutable;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureSubscriptionAllowsWrites
{
    public function __construct(private readonly SubscriptionRepositoryInterface $subscriptions) {}

    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->isMethodSafe() && ! $this->subscriptions->currentWithPlan()->isUsable(CarbonImmutable::now())) {
            throw new SubscriptionInactive;
        }

        return $next($request);
    }
}
