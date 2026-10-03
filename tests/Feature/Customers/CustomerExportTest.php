<?php

declare(strict_types=1);

use App\Enums\CustomerStatus;
use App\Enums\ExportStatus;
use App\Enums\TenantRole;
use App\Jobs\ExportCustomersJob;
use App\Models\CustomerExport;
use App\Services\CustomerExportService;
use App\Support\Tenancy\TenantContext;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;

beforeEach(function (): void {
    Storage::fake('local');
});

/** @return list<list<string>> */
function exportedRows(string $exportUlid): array
{
    $export = CustomerExport::withoutGlobalScopes()->where('ulid', $exportUlid)->sole();
    $lines = array_filter(explode("\n", Storage::disk('local')->get($export->file_path)));

    return array_map(str_getcsv(...), array_values($lines));
}

it('returns 403 FEATURE_NOT_AVAILABLE on the free plan', function (): void {
    actingAsTenantUser(TenantRole::Owner, 'free');

    $this->postJson('api/v1/customers/exports')
        ->assertForbidden()
        ->assertJsonPath('error.code', 'FEATURE_NOT_AVAILABLE')
        ->assertJsonPath('error.details.feature', 'customer_export');
});

it('returns 403 to a member', function (): void {
    actingAsTenantUser(TenantRole::Member, 'pro');

    $this->postJson('api/v1/customers/exports')->assertForbidden()->assertJsonPath('error.code', 'FORBIDDEN');
});

it('exports only this tenant rows that match the filter', function (): void {
    $user = actingAsTenantUser(TenantRole::Admin, 'pro');
    createCustomers($user->tenant, 3, ['status' => CustomerStatus::Active]);
    createCustomers($user->tenant, 2, ['status' => CustomerStatus::Inactive]);
    createCustomers(createTenant(), 4, ['status' => CustomerStatus::Active]);

    $id = $this->postJson('api/v1/customers/exports', ['filter' => ['status' => 'active']])
        ->assertStatus(202)
        ->assertJsonPath('data.status', 'pending')
        ->json('data.id');

    $this->getJson("api/v1/customers/exports/{$id}")
        ->assertOk()
        ->assertJsonPath('data.status', 'completed')
        ->assertJsonPath('data.row_count', 3)
        ->assertJsonPath('data.download_url', route('customers.exports.download', $id));

    $rows = exportedRows($id);
    expect($rows[0])->toBe(['id', 'name', 'email', 'phone', 'company_name', 'status', 'created_at'])
        ->and(array_slice($rows, 1))->toHaveCount(3)
        ->and(array_unique(array_column(array_slice($rows, 1), 5)))->toBe(['active']);

    $this->get("api/v1/customers/exports/{$id}/download")
        ->assertOk()
        ->assertDownload("customers-{$id}.csv");
});

it('escapes cells that would run as spreadsheet formulas', function (): void {
    $user = actingAsTenantUser(TenantRole::Owner, 'pro');
    createCustomers($user->tenant, 1, ['name' => '=HYPERLINK("http://evil.test")', 'company_name' => '@SUM(A1)', 'phone' => '+8801700000000']);

    $id = $this->postJson('api/v1/customers/exports')->assertStatus(202)->json('data.id');

    $row = exportedRows($id)[1];
    expect($row[1])->toBe('\'=HYPERLINK("http://evil.test")')
        ->and($row[3])->toBe("'+8801700000000")
        ->and($row[4])->toBe("'@SUM(A1)");
});

it('returns 409 EXPORT_NOT_READY when downloading before completion', function (): void {
    Queue::fake();
    actingAsTenantUser(TenantRole::Owner, 'pro');

    $id = $this->postJson('api/v1/customers/exports')->assertStatus(202)->json('data.id');

    Queue::assertPushedOn('low', ExportCustomersJob::class);
    $this->getJson("api/v1/customers/exports/{$id}")->assertJsonPath('data.download_url', null);
    $this->getJson("api/v1/customers/exports/{$id}/download")
        ->assertStatus(409)
        ->assertJsonPath('error.code', 'EXPORT_NOT_READY');
});

it('marks the export failed with a safe message when the job fails', function (): void {
    Queue::fake();
    $user = actingAsTenantUser(TenantRole::Owner, 'pro');
    $id = $this->postJson('api/v1/customers/exports')->json('data.id');
    $export = CustomerExport::withoutGlobalScopes()->where('ulid', $id)->sole();

    (new ExportCustomersJob($user->tenant_id, $export->id))->failed(new RuntimeException('disk full at /var/secret'));

    $this->getJson("api/v1/customers/exports/{$id}")
        ->assertJsonPath('data.status', 'failed')
        ->assertJsonPath('data.error_message', 'Export failed. Please try again.')
        ->assertDontSee('/var/secret');
});

it('does nothing when the job runs again after completion', function (): void {
    $user = actingAsTenantUser(TenantRole::Owner, 'pro');
    createCustomers($user->tenant, 2);
    $id = $this->postJson('api/v1/customers/exports')->json('data.id');
    $export = CustomerExport::withoutGlobalScopes()->where('ulid', $id)->sole();
    createCustomers($user->tenant, 5);

    app(CustomerExportService::class)->process($user->tenant_id, $export->id);

    expect($export->fresh()->row_count)->toBe(2)
        ->and($export->fresh()->status)->toBe(ExportStatus::Completed);
});

it('allows 5 export requests per hour per tenant', function (): void {
    Queue::fake();
    $owner = actingAsTenantUser(TenantRole::Owner, 'pro');

    foreach (range(1, 5) as $ignored) {
        $this->postJson('api/v1/customers/exports')->assertStatus(202);
    }

    $this->actingAs(createTenantUser($owner->tenant, TenantRole::Admin), 'sanctum')
        ->postJson('api/v1/customers/exports')
        ->assertStatus(429)
        ->assertJsonPath('error.code', 'RATE_LIMITED');
});

it('runs the job inside the tenant context only', function (): void {
    $user = actingAsTenantUser(TenantRole::Owner, 'pro');
    Queue::fake();
    $id = $this->postJson('api/v1/customers/exports')->json('data.id');
    $export = CustomerExport::withoutGlobalScopes()->where('ulid', $id)->sole();
    app(TenantContext::class)->clear();

    (new ExportCustomersJob($user->tenant_id, $export->id))->handle(app(CustomerExportService::class));

    expect(app(TenantContext::class)->has())->toBeFalse()
        ->and($export->fresh()->status)->toBe(ExportStatus::Completed);
});
