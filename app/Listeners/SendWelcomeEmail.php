<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\TenantRegistered;
use App\Mail\WelcomeMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

final class SendWelcomeEmail implements ShouldQueue
{
    public string $queue = 'high';

    public int $tries = 3;

    /** @var list<int> */
    public array $backoff = [10, 60];

    public function handle(TenantRegistered $event): void
    {
        Mail::to($event->ownerEmail)->send(new WelcomeMail($event->ownerName, $event->companyName));
    }
}
