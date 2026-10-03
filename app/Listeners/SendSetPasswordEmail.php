<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Events\UserCreated;
use App\Services\AuthService;
use Illuminate\Contracts\Queue\ShouldQueue;

final class SendSetPasswordEmail implements ShouldQueue
{
    public string $queue = 'high';

    public int $tries = 3;

    /** @var list<int> */
    public array $backoff = [10, 60];

    public function __construct(private readonly AuthService $auth) {}

    public function handle(UserCreated $event): void
    {
        $this->auth->sendResetLink($event->email);
    }
}
