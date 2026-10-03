<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;

final readonly class RegistrationResult
{
    public function __construct(
        public Tenant $tenant,
        public User $user,
        public Subscription $subscription,
        public string $token,
    ) {}
}
