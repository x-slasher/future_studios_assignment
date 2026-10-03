<?php

declare(strict_types=1);

namespace App\Events;

use App\Events\Contracts\PlatformDataChanged;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;

final class PlanChanged implements PlatformDataChanged, ShouldDispatchAfterCommit
{
    use Dispatchable;

    public function __construct(public readonly int $planId) {}
}
