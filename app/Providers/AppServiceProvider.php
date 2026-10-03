<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\FeatureGate;
use App\Enums\FeatureKey;
use App\Events\Contracts\PlatformDataChanged;
use App\Events\Contracts\TenantDataChanged;
use App\Events\TenantRegistered;
use App\Events\UserCreated;
use App\Listeners\InvalidatePlatformCache;
use App\Listeners\InvalidateTenantCache;
use App\Listeners\SendSetPasswordEmail;
use App\Listeners\SendWelcomeEmail;
use App\Models\User;
use App\Policies\UserPolicy;
use App\Services\PlanFeatureGate;
use App\Support\Tenancy\TenantContext;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Scoped: reset between requests and queued jobs.
        $this->app->scoped(TenantContext::class);
        $this->app->bind(FeatureGate::class, PlanFeatureGate::class);
    }

    public function boot(): void
    {
        Model::shouldBeStrict(! $this->app->isProduction());

        Event::listen(TenantDataChanged::class, InvalidateTenantCache::class);
        Event::listen(PlatformDataChanged::class, InvalidatePlatformCache::class);
        Event::listen(TenantRegistered::class, SendWelcomeEmail::class);
        Event::listen(UserCreated::class, SendSetPasswordEmail::class);

        Gate::policy(User::class, UserPolicy::class);

        $this->configureRateLimiting();

        // Rejects a token issued while its user was being deactivated.
        Sanctum::authenticateAccessTokensUsing(
            fn (PersonalAccessToken $token, bool $isValid): bool => $isValid && $token->tokenable?->is_active === true,
        );
    }

    private function configureRateLimiting(): void
    {
        // Per email+IP against guessing one account; per IP against trying many emails.
        RateLimiter::for('auth', function (Request $request): array {
            $email = $request->input('email');
            $ip = 'ip:'.$request->ip();

            return [
                Limit::perMinute(5)->by(is_string($email) && $email !== '' ? Str::lower($email).'|'.$request->ip() : $ip),
                Limit::perMinute(20)->by($ip),
            ];
        });

        RateLimiter::for('exports', fn (Request $request): Limit => Limit::perHour(5)
            ->by('tenant:'.$this->app->make(TenantContext::class)->id()));

        RateLimiter::for('public', fn (Request $request): Limit => Limit::perMinute(60)->by('ip:'.$request->ip()));

        RateLimiter::for('api', function (Request $request): Limit {
            $user = $request->user();
            $context = $this->app->make(TenantContext::class);

            if ($user->is_platform_admin) {
                return Limit::perMinute(1000)->by('user:'.$user->id);
            }

            if (! $context->has()) {
                return Limit::perMinute(60)->by('user:'.$user->id);
            }

            $perMinute = $this->app->make(FeatureGate::class)->limit($context->tenant(), FeatureKey::ApiRatePerMinute) ?? 1000;

            return Limit::perMinute($perMinute)->by('user:'.$user->id);
        });
    }
}
