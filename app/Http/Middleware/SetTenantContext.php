<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Support\Tenancy\TenantContext;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class SetTenantContext
{
    public const string REQUIRED = 'required';

    public const string OPTIONAL = 'optional';

    public function __construct(private readonly TenantContext $context) {}

    public function handle(Request $request, Closure $next, string $mode = self::REQUIRED): Response
    {
        $user = $request->user();

        if ($user->is_platform_admin || $user->tenant_id === null) {
            if ($mode !== self::OPTIONAL) {
                throw new AuthorizationException;
            }

            $this->context->clear();

            return $next($request);
        }

        $this->context->set($user->tenant);

        return $next($request);
    }
}
