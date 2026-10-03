<?php

declare(strict_types=1);

use App\Jobs\ProcessSubscriptionLifecycleJob;
use App\Jobs\TakeDailyUsageSnapshotsJob;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new ProcessSubscriptionLifecycleJob)->hourly()->withoutOverlapping();
Schedule::job(new TakeDailyUsageSnapshotsJob)->dailyAt('00:15')->withoutOverlapping();
