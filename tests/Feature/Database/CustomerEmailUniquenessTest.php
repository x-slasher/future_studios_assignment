<?php

declare(strict_types=1);

use App\Models\Customer;
use App\Models\Tenant;
use Illuminate\Database\UniqueConstraintViolationException;

it('rejects a second non-deleted customer with the same email in one tenant', function (): void {
    $tenant = Tenant::factory()->create();
    Customer::factory()->create(['tenant_id' => $tenant->id, 'email' => 'same@example.test']);

    Customer::factory()->create(['tenant_id' => $tenant->id, 'email' => 'same@example.test']);
})->throws(UniqueConstraintViolationException::class);

it('accepts the same email again after the first customer is soft-deleted', function (): void {
    $tenant = Tenant::factory()->create();
    $first = Customer::factory()->create(['tenant_id' => $tenant->id, 'email' => 'same@example.test']);

    $first->delete();
    $second = Customer::factory()->create(['tenant_id' => $tenant->id, 'email' => 'same@example.test']);

    expect($second->exists)->toBeTrue();
    $this->assertDatabaseCount('customers', 2);
});

it('accepts the same email in two different tenants', function (): void {
    [$a, $b] = Tenant::factory()->count(2)->create();

    Customer::factory()->create(['tenant_id' => $a->id, 'email' => 'same@example.test']);
    Customer::factory()->create(['tenant_id' => $b->id, 'email' => 'same@example.test']);

    $this->assertDatabaseCount('customers', 2);
});
