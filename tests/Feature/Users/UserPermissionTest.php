<?php

declare(strict_types=1);

use App\DTOs\UpdateUserData;
use App\Enums\TenantRole;
use App\Exceptions\LastOwnerException;
use App\Models\User;
use App\Services\UserService;
use App\Support\Tenancy\TenantContext;
use Laravel\Sanctum\Sanctum;

beforeEach(function (): void {
    $this->tenant = createTenant('pro');
    $this->owner = createTenantUser($this->tenant, TenantRole::Owner);
    $this->admin = createTenantUser($this->tenant, TenantRole::Admin);
    $this->member = createTenantUser($this->tenant, TenantRole::Member);
});

it('forbids a member from creating, updating, or deleting users', function (): void {
    Sanctum::actingAs($this->member);
    $target = createTenantUser($this->tenant, TenantRole::Member);

    $this->getJson('api/v1/users')->assertOk();
    $this->postJson('api/v1/users', ['name' => 'X', 'email' => 'x@acme.test', 'role' => 'member'])
        ->assertForbidden()
        ->assertJsonPath('error.code', 'FORBIDDEN');
    $this->patchJson("api/v1/users/{$target->ulid}", ['name' => 'X'])->assertForbidden();
    $this->deleteJson("api/v1/users/{$target->ulid}")->assertForbidden();
});

it('forbids an admin from updating or deleting an owner', function (): void {
    Sanctum::actingAs($this->admin);

    $this->patchJson("api/v1/users/{$this->owner->ulid}", ['name' => 'X'])->assertForbidden();
    $this->deleteJson("api/v1/users/{$this->owner->ulid}")->assertForbidden();
});

it('lets an admin manage members', function (): void {
    Sanctum::actingAs($this->admin);

    $this->patchJson("api/v1/users/{$this->member->ulid}", ['role' => 'admin'])->assertOk();
    $this->deleteJson("api/v1/users/{$this->member->ulid}")->assertNoContent();
});

it('forbids an admin from assigning the owner role', function (): void {
    Sanctum::actingAs($this->admin);

    $this->postJson('api/v1/users', ['name' => 'X', 'email' => 'x@acme.test', 'role' => 'owner'])->assertForbidden();
    $this->patchJson("api/v1/users/{$this->member->ulid}", ['role' => 'owner'])->assertForbidden();
});

it('lets an owner assign the owner role', function (): void {
    Sanctum::actingAs($this->owner);

    $this->patchJson("api/v1/users/{$this->admin->ulid}", ['role' => 'owner'])
        ->assertOk()
        ->assertJsonPath('data.role', 'owner');
});

it('forbids a user from deleting themselves', function (TenantRole $role): void {
    $self = match ($role) {
        TenantRole::Owner => $this->owner,
        TenantRole::Admin => $this->admin,
        TenantRole::Member => $this->member,
    };
    Sanctum::actingAs($self);

    $this->deleteJson("api/v1/users/{$self->ulid}")->assertForbidden();
})->with([TenantRole::Owner, TenantRole::Admin]);

it('returns 409 LAST_OWNER when the last owner is demoted or deactivated', function (array $change): void {
    Sanctum::actingAs($this->owner);

    $this->patchJson("api/v1/users/{$this->owner->ulid}", $change)
        ->assertStatus(409)
        ->assertJsonPath('error.code', 'LAST_OWNER');

    expect($this->owner->fresh()->is_active)->toBeTrue();
})->with([
    'demoted' => [['role' => 'admin']],
    'deactivated' => [['is_active' => false]],
]);

it('allows an owner to step down once a second owner exists', function (): void {
    Sanctum::actingAs($this->owner);
    $this->patchJson("api/v1/users/{$this->admin->ulid}", ['role' => 'owner'])->assertOk();

    $this->patchJson("api/v1/users/{$this->owner->ulid}", ['role' => 'admin'])->assertOk();
});

it('refuses to delete the last owner in the service', function (): void {
    app(TenantContext::class)->run($this->tenant, function (): void {
        app(UserService::class)->delete($this->owner);
    });
})->throws(LastOwnerException::class);

it('refuses to deactivate the last owner in the service even without the policy', function (): void {
    app(TenantContext::class)->run($this->tenant, function (): void {
        app(UserService::class)->update($this->owner, new UpdateUserData(null, null, false));
    });
})->throws(LastOwnerException::class);

it('does not count an inactive owner as an owner', function (): void {
    $inactiveOwner = createTenantUser($this->tenant, TenantRole::Owner);
    $inactiveOwner->update(['is_active' => false]);
    Sanctum::actingAs($this->owner);

    $this->patchJson("api/v1/users/{$this->owner->ulid}", ['role' => 'member'])->assertStatus(409);
    expect(User::query()->find($this->owner->id)->is_active)->toBeTrue();
});

it('forbids a member from deleting customers but lets them create and update', function (): void {
    Sanctum::actingAs($this->member);
    $customer = createCustomers($this->tenant)->first();

    $this->postJson('api/v1/customers', ['name' => 'X', 'email' => 'x@store.test'])->assertCreated();
    $this->patchJson("api/v1/customers/{$customer->ulid}", ['name' => 'Y'])->assertOk();
    $this->deleteJson("api/v1/customers/{$customer->ulid}")->assertForbidden()->assertJsonPath('error.code', 'FORBIDDEN');
});
