<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\UsageSnapshotService;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

final class TakeDailyUsageSnapshotsJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 1;

    public function __construct()
    {
        $this->onQueue('default');
    }

    public function handle(UsageSnapshotService $snapshots): void
    {
        $snapshots->takeDaily(CarbonImmutable::today());
    }
}
