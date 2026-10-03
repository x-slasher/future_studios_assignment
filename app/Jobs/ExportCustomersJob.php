<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\CustomerExportService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

final class ExportCustomersJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 300;

    /** @var list<int> */
    public array $backoff = [30, 120];

    public function __construct(
        public readonly int $tenantId,
        public readonly int $exportId,
    ) {
        $this->onQueue('low');
    }

    public function handle(CustomerExportService $exports): void
    {
        $exports->process($this->tenantId, $this->exportId);
    }

    public function failed(?Throwable $exception): void
    {
        app(CustomerExportService::class)->markFailed($this->tenantId, $this->exportId);
    }
}
