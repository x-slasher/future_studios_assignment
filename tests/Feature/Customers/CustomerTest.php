<?php

declare(strict_types=1);

use App\Enums\TenantRole;
use Illuminate\Support\Facades\DB;

beforeEach(function (): void {
    $this->user = actingAsTenantUser(TenantRole::Member, 'pro');
    $this->tenant = $this->user->tenant;
});

it('creates, shows, updates, and deletes a customer', function (): void {
    $id = $this->postJson('api/v1/customers', [
        'name' => 'Karim Store',
        'email' => 'karim@store.test',
        'phone' => '+8801700000000',
        'company_name' => 'Karim Ltd',
    ])
        ->assertCreated()
        ->assertJsonPath('data.status', 'active')
        ->assertJsonPath('data.created_by', ['id' => $this->user->ulid, 'name' => $this->user->name])
        ->json('data.id');

    $this->getJson("api/v1/customers/{$id}")
        ->assertOk()
        ->assertJsonPath('data.email', 'karim@store.test')
        ->assertJsonPath('data.created_by.id', $this->user->ulid);

    $this->patchJson("api/v1/customers/{$id}", ['status' => 'inactive', 'phone' => null])
        ->assertOk()
        ->assertJsonPath('data.status', 'inactive')
        ->assertJsonPath('data.phone', null)
        ->assertJsonPath('data.company_name', 'Karim Ltd');

    $owner = createTenantUser($this->tenant, TenantRole::Owner);
    $this->actingAs($owner, 'sanctum')->deleteJson("api/v1/customers/{$id}")->assertNoContent();
    $this->getJson("api/v1/customers/{$id}")->assertNotFound();

    expect(DB::table('customers')->where('ulid', $id)->value('deleted_at'))->not->toBeNull();
});

it('never exposes numeric ids or tenant_id', function (): void {
    $response = $this->postJson('api/v1/customers', ['name' => 'Karim', 'email' => 'karim@store.test'])
        ->assertCreated()
        ->assertExactJsonStructure(['data' => [
            'id', 'name', 'email', 'phone', 'company_name', 'status', 'created_by' => ['id', 'name'], 'created_at', 'updated_at',
        ]]);

    expect($response->json('data.id'))->toBeString()->toHaveLength(26)
        ->and($response->json('data.created_by.id'))->toHaveLength(26)
        ->and($response->getContent())->not->toContain('tenant_id')->not->toContain('created_by_user_id');
});

it('allows the same email in two tenants but not twice in one tenant', function (): void {
    createCustomers(createTenant(), 1, ['email' => 'shared@store.test']);

    $this->postJson('api/v1/customers', ['name' => 'A', 'email' => 'shared@store.test'])->assertCreated();
    $this->postJson('api/v1/customers', ['name' => 'B', 'email' => 'shared@store.test'])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['email'], 'error.details');
});

it('rejects changing an email to one another customer uses, but allows keeping its own', function (): void {
    [$a, $b] = createCustomers($this->tenant, 2);

    $this->patchJson("api/v1/customers/{$a->ulid}", ['email' => $b->email])->assertStatus(422);
    $this->patchJson("api/v1/customers/{$a->ulid}", ['email' => $a->email, 'name' => 'Same'])->assertOk();
});

it('lets a deleted customer email be used again', function (): void {
    $customer = createCustomers($this->tenant, 1, ['email' => 'again@store.test'])->first();
    $this->actingAs(createTenantUser($this->tenant, TenantRole::Owner), 'sanctum');

    $this->deleteJson("api/v1/customers/{$customer->ulid}")->assertNoContent();

    $this->postJson('api/v1/customers', ['name' => 'Again', 'email' => 'again@store.test'])->assertCreated();
    expect(DB::table('customers')->where('email', 'again@store.test')->count())->toBe(2);
});

it('validates input', function (): void {
    $this->postJson('api/v1/customers', ['email' => 'nope', 'status' => 'vip'])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'email', 'status'], 'error.details');
});
