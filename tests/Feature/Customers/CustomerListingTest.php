<?php

declare(strict_types=1);

use App\Enums\CustomerStatus;
use App\Enums\TenantRole;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;

beforeEach(function (): void {
    $this->user = actingAsTenantUser(TenantRole::Member, 'pro');
    $this->tenant = $this->user->tenant;
});

function customerAt(string $name, string $createdAt, CustomerStatus $status = CustomerStatus::Active, ?string $email = null): void
{
    test()->travelTo(CarbonImmutable::parse($createdAt));
    createCustomers(test()->tenant, 1, ['name' => $name, 'status' => $status, 'email' => $email ?? strtolower($name).'@store.test']);
    test()->travelBack();
}

it('sorts newest first by default', function (): void {
    customerAt('Old', '2026-09-01 10:00:00');
    customerAt('New', '2026-09-20 10:00:00');
    customerAt('Mid', '2026-09-10 10:00:00');

    $this->getJson('api/v1/customers')->assertOk()->assertJsonPath('data.*.name', ['New', 'Mid', 'Old']);
    $this->getJson('api/v1/customers?sort=created_at')->assertJsonPath('data.*.name', ['Old', 'Mid', 'New']);
});

it('filters by status', function (): void {
    customerAt('Active One', '2026-09-01');
    customerAt('Sleeping', '2026-09-02', CustomerStatus::Inactive);

    $this->getJson('api/v1/customers?filter[status]=inactive')->assertJsonPath('data.*.name', ['Sleeping']);
});

it('searches by prefix on name or email, not by substring', function (): void {
    customerAt('Rahim Traders', '2026-09-01', email: 'contact@rahim.test');
    customerAt('Karim Store', '2026-09-02', email: 'rahim.k@store.test');
    customerAt('Abrahim', '2026-09-03', email: 'abr@store.test');

    $this->getJson('api/v1/customers?filter[search]=rahim&sort=name')
        ->assertJsonPath('data.*.name', ['Karim Store', 'Rahim Traders']);
});

it('escapes LIKE wildcards in the search term', function (): void {
    customerAt('Anything', '2026-09-01');

    $this->getJson('api/v1/customers?filter[search]=%25')->assertJsonCount(0, 'data');
    $this->getJson('api/v1/customers?filter[search]=_')->assertJsonCount(0, 'data');
});

it('filters by created_from and created_to, inclusive of whole days', function (): void {
    customerAt('August', '2026-08-31 23:59:59');
    customerAt('SeptFirst', '2026-09-01 00:00:00');
    customerAt('SeptTenth', '2026-09-10 23:59:59');
    customerAt('SeptEleventh', '2026-09-11 00:00:00');

    $this->getJson('api/v1/customers?filter[created_from]=2026-09-01&filter[created_to]=2026-09-10&sort=created_at')
        ->assertJsonPath('data.*.name', ['SeptFirst', 'SeptTenth']);
});

it('sorts by name both ways', function (): void {
    customerAt('Bravo', '2026-09-01');
    customerAt('Alpha', '2026-09-02');
    customerAt('Charlie', '2026-09-03');

    $this->getJson('api/v1/customers?sort=name')->assertJsonPath('data.*.name', ['Alpha', 'Bravo', 'Charlie']);
    $this->getJson('api/v1/customers?sort=-name')->assertJsonPath('data.*.name', ['Charlie', 'Bravo', 'Alpha']);
});

it('returns 422 for an unknown sort, filter key, bad date, or per_page over 100', function (string $query): void {
    $this->getJson("api/v1/customers?{$query}")
        ->assertStatus(422)
        ->assertJsonPath('error.code', 'VALIDATION_FAILED');
})->with([
    'sort=email',
    'sort=-email',
    'filter[tenant_id]=1',
    'filter[phone]=1',
    'filter[status]=vip',
    'filter[created_from]=yesterday',
    'filter[created_from]=2026-09-10&filter[created_to]=2026-09-01',
    'per_page=101',
    'per_page=0',
    'page=0',
    'page=9223372036854775807',
]);

it('returns correct pagination meta and links', function (): void {
    createCustomers($this->tenant, 23);

    $this->getJson('api/v1/customers?per_page=10&page=3')
        ->assertOk()
        ->assertJsonCount(3, 'data')
        ->assertJsonPath('meta.current_page', 3)
        ->assertJsonPath('meta.per_page', 10)
        ->assertJsonPath('meta.total', 23)
        ->assertJsonPath('meta.last_page', 3)
        ->assertJsonPath('links.next', null);

    $this->getJson('api/v1/customers')->assertJsonPath('meta.per_page', 15)->assertJsonCount(15, 'data');
});

it('keeps the filters and sort in the pagination links', function (): void {
    createCustomers($this->tenant, 5);

    $next = $this->getJson('api/v1/customers?filter[status]=active&sort=name&per_page=2')->json('links.next');

    expect(urldecode($next))->toContain('filter[status]=active')->toContain('sort=name')->toContain('per_page=2')->toContain('page=2');
});

it('runs a fixed number of queries regardless of row count', function (): void {
    $creators = collect(range(1, 5))->map(fn (): mixed => createTenantUser($this->tenant, TenantRole::Member));
    $countQueries = function (): int {
        DB::flushQueryLog();
        DB::enableQueryLog();
        $this->getJson('api/v1/customers?per_page=100')->assertOk();
        DB::disableQueryLog();

        return count(DB::getQueryLog());
    };

    $creators->each(fn ($creator) => createCustomers($this->tenant, 1, ['created_by_user_id' => $creator->id]));
    $countQueries();
    $forFive = $countQueries();

    createCustomers($this->tenant, 45, ['created_by_user_id' => $creators->first()->id]);
    $forFifty = $countQueries();

    expect($forFifty)->toBe($forFive);
});
