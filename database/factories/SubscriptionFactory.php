<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\SubscriptionStatus;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Subscription> */
class SubscriptionFactory extends Factory
{
    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'plan_id' => fn (): int => $this->planId('pro'),
            'status' => SubscriptionStatus::Active,
            'current_period_start' => now(),
            'current_period_end' => now()->addMonth(),
        ];
    }

    public function forPlan(string $code): static
    {
        return $this->state(fn (): array => ['plan_id' => $this->planId($code)]);
    }

    public function trialing(): static
    {
        return $this->state(fn (): array => [
            'status' => SubscriptionStatus::Trialing,
            'trial_ends_at' => now()->addDays(14),
            'current_period_start' => null,
            'current_period_end' => null,
        ]);
    }

    public function active(): static
    {
        return $this->state(fn (): array => [
            'status' => SubscriptionStatus::Active,
            'current_period_start' => now(),
            'current_period_end' => now()->addMonth(),
        ]);
    }

    public function activeFree(): static
    {
        return $this->state(fn (): array => [
            'plan_id' => $this->planId('free'),
            'status' => SubscriptionStatus::Active,
            'current_period_start' => null,
            'current_period_end' => null,
        ]);
    }

    public function pastDue(): static
    {
        return $this->state(fn (): array => [
            'status' => SubscriptionStatus::PastDue,
            'current_period_start' => now()->subMonth()->subDay(),
            'current_period_end' => now()->subDay(),
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn (): array => [
            'status' => SubscriptionStatus::Cancelled,
            'current_period_start' => now()->subDays(20),
            'current_period_end' => now()->addDays(10),
            'cancelled_at' => now(),
            'ends_at' => now()->addDays(10),
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn (): array => [
            'status' => SubscriptionStatus::Expired,
            'current_period_start' => now()->subMonths(2),
            'current_period_end' => now()->subMonth(),
        ]);
    }

    private function planId(string $code): int
    {
        return Plan::query()->where('code', $code)->valueOrFail('id');
    }
}
