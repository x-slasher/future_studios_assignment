<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Services\SubscriptionService;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

final class ProcessSubscriptionLifecycleJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 1;

    public function __construct()
    {
        $this->onQueue('default');
    }

    public function handle(SubscriptionService $subscriptions): void
    {
        $subscriptions->processLifecycle(CarbonImmutable::now());
    }
}
