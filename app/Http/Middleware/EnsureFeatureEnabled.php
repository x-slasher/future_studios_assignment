<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Contracts\FeatureGate;
use App\Enums\FeatureKey;
use App\Support\Tenancy\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class EnsureFeatureEnabled
{
    public function __construct(
        private readonly FeatureGate $features,
        private readonly TenantContext $context,
    ) {}

    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $this->features->ensureEnabled($this->context->tenant(), FeatureKey::from($feature));

        return $next($request);
    }
}
