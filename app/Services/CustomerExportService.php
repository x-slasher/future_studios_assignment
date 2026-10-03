<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\CustomerFilter;
use App\Enums\CustomerStatus;
use App\Enums\ExportStatus;
use App\Exceptions\ExportNotReady;
use App\Jobs\ExportCustomersJob;
use App\Models\Customer;
use App\Models\CustomerExport;
use App\Models\User;
use App\Repositories\Contracts\CustomerExportRepositoryInterface;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use App\Repositories\Contracts\TenantRepositoryInterface;
use App\Support\Tenancy\TenantContext;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Storage;

final class CustomerExportService
{
    private const string DISK = 'local';

    private const array COLUMNS = ['id', 'name', 'email', 'phone', 'company_name', 'status', 'created_at'];

    // Cells starting with these run as formulas in spreadsheets.
    private const array FORMULA_PREFIXES = ['=', '+', '-', '@', "\t", "\r"];

    public function __construct(
        private readonly CustomerExportRepositoryInterface $exports,
        private readonly CustomerRepositoryInterface $customers,
        private readonly TenantRepositoryInterface $tenants,
        private readonly TenantContext $context,
    ) {}

    public function request(CustomerFilter $filter, User $actor): CustomerExport
    {
        $export = $this->exports->create([
            'requested_by_user_id' => $actor->id,
            'status' => ExportStatus::Pending,
            'filters' => $this->filterToArray($filter),
        ]);

        ExportCustomersJob::dispatch($export->tenant_id, $export->id);

        return $export;
    }

    public function process(int $tenantId, int $exportId): void
    {
        $tenant = $this->tenants->findById($tenantId);

        $this->context->run($tenant, function () use ($tenant, $exportId): void {
            $export = $this->exports->findById($exportId);

            if ($export->status === ExportStatus::Completed) {
                return;
            }

            $this->exports->update($export, ['status' => ExportStatus::Processing]);
            $path = sprintf('exports/%s/%s.csv', $tenant->ulid, $export->ulid);
            $rowCount = $this->writeCsv($path, $this->filterFromArray($export->filters ?? []));

            $this->exports->update($export, [
                'status' => ExportStatus::Completed,
                'file_path' => $path,
                'row_count' => $rowCount,
                'completed_at' => CarbonImmutable::now(),
            ]);
        });
    }

    public function markFailed(int $tenantId, int $exportId): void
    {
        $this->context->run($this->tenants->findById($tenantId), function () use ($exportId): void {
            $this->exports->update($this->exports->findById($exportId), [
                'status' => ExportStatus::Failed,
                'error_message' => 'Export failed. Please try again.',
            ]);
        });
    }

    /** @throws ExportNotReady */
    public function downloadPath(CustomerExport $export): string
    {
        if ($export->status !== ExportStatus::Completed || $export->file_path === null) {
            throw new ExportNotReady;
        }

        return $export->file_path;
    }

    public function disk(): string
    {
        return self::DISK;
    }

    private function writeCsv(string $path, CustomerFilter $filter): int
    {
        $stream = fopen('php://temp', 'w+b');
        fputcsv($stream, self::COLUMNS, escape: '');
        $rowCount = 0;

        foreach ($this->customers->lazyForExport($filter) as $customer) {
            fputcsv($stream, $this->row($customer), escape: '');
            $rowCount++;
        }

        rewind($stream);
        Storage::disk(self::DISK)->writeStream($path, $stream);
        fclose($stream);

        return $rowCount;
    }

    /** @return list<string> */
    private function row(Customer $customer): array
    {
        return array_map($this->escapeFormula(...), [
            $customer->ulid,
            $customer->name,
            $customer->email,
            $customer->phone ?? '',
            $customer->company_name ?? '',
            $customer->status->value,
            $customer->created_at->toIso8601ZuluString(),
        ]);
    }

    private function escapeFormula(string $value): string
    {
        return $value !== '' && in_array($value[0], self::FORMULA_PREFIXES, true) ? "'".$value : $value;
    }

    /** @return array<string, string|null> */
    private function filterToArray(CustomerFilter $filter): array
    {
        return [
            'status' => $filter->status?->value,
            'search' => $filter->search,
            'created_from' => $filter->createdFrom?->toDateString(),
            'created_to' => $filter->createdTo?->toDateString(),
        ];
    }

    /** @param array<string, string|null> $filters */
    private function filterFromArray(array $filters): CustomerFilter
    {
        return new CustomerFilter(
            status: CustomerStatus::tryFrom((string) ($filters['status'] ?? '')),
            search: $filters['search'] ?? null,
            createdFrom: isset($filters['created_from']) ? CarbonImmutable::parse($filters['created_from']) : null,
            createdTo: isset($filters['created_to']) ? CarbonImmutable::parse($filters['created_to']) : null,
        );
    }
}
