<?php

declare(strict_types=1);

use App\Enums\TenantRole;

it('lets a free plan user make 60 requests a minute and returns 429 RATE_LIMITED on the 61st', function (): void {
    actingAsTenantUser(TenantRole::Owner, 'free');

    foreach (range(1, 60) as $ignored) {
        $this->getJson('api/v1/tenant')->assertOk();
    }

    $this->getJson('api/v1/tenant')
        ->assertStatus(429)
        ->assertHeader('Retry-After')
        ->assertHeader('X-RateLimit-Limit', '60')
        ->assertJsonPath('error.code', 'RATE_LIMITED');
});

it('does not limit a pro plan user at 61', function (): void {
    actingAsTenantUser(TenantRole::Owner, 'pro');

    foreach (range(1, 61) as $ignored) {
        $this->getJson('api/v1/tenant')->assertOk();
    }

    $this->getJson('api/v1/tenant')->assertHeader('X-RateLimit-Limit', '600');
});

it('limits each user separately, not the whole tenant', function (): void {
    $first = actingAsTenantUser(TenantRole::Owner, 'free');
    foreach (range(1, 60) as $ignored) {
        $this->getJson('api/v1/tenant');
    }
    $this->getJson('api/v1/tenant')->assertStatus(429);

    $this->actingAs(createTenantUser($first->tenant, TenantRole::Member), 'sanctum')
        ->getJson('api/v1/tenant')
        ->assertOk();
});

it('gives a platform admin 1000 requests a minute', function (): void {
    actingAsPlatformAdmin();

    $this->getJson('api/v1/admin/plans')->assertOk()->assertHeader('X-RateLimit-Limit', '1000');
});

it('returns 429 on the 6th login attempt in a minute', function (): void {
    foreach (range(1, 5) as $ignored) {
        $this->postJson('api/v1/auth/login', ['email' => 'Someone@Acme.test', 'password' => 'wrong'])->assertUnauthorized();
    }

    $this->postJson('api/v1/auth/login', ['email' => 'someone@acme.test', 'password' => 'wrong'])
        ->assertStatus(429)
        ->assertHeader('Retry-After')
        ->assertJsonPath('error.code', 'RATE_LIMITED');

    $this->postJson('api/v1/auth/login', ['email' => 'other@acme.test', 'password' => 'wrong'])->assertUnauthorized();
});

it('limits public plan listing to 60 per minute per IP', function (): void {
    foreach (range(1, 60) as $ignored) {
        $this->getJson('api/v1/plans')->assertOk();
    }

    $this->getJson('api/v1/plans')->assertStatus(429)->assertJsonPath('error.code', 'RATE_LIMITED');
});

it('limits one IP to 20 auth requests a minute across different emails', function (): void {
    foreach (range(1, 20) as $i) {
        $this->postJson('api/v1/auth/login', ['email' => "user{$i}@acme.test", 'password' => 'wrong'])->assertUnauthorized();
    }

    $this->postJson('api/v1/auth/login', ['email' => 'user21@acme.test', 'password' => 'wrong'])
        ->assertStatus(429)
        ->assertJsonPath('error.code', 'RATE_LIMITED');
});
