<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\DTOs\CreateCustomerData;
use App\DTOs\CreateUserData;
use App\DTOs\RegisterTenantData;
use App\Enums\CustomerStatus;
use App\Enums\TenantRole;
use App\Models\Customer;
use App\Models\DailyUsageSnapshot;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\CustomerService;
use App\Services\SubscriptionService;
use App\Services\TenantService;
use App\Services\UserService;
use App\Support\Tenancy\TenantContext;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    private const string PASSWORD = 'password';

    public function __construct(
        private readonly TenantService $tenants,
        private readonly UserService $users,
        private readonly CustomerService $customers,
        private readonly SubscriptionService $subscriptions,
        private readonly UserRepositoryInterface $userRepository,
        private readonly TenantContext $context,
    ) {}

    public function run(): void
    {
        User::factory()->platformAdmin()->create(['name' => 'Platform Admin', 'email' => 'admin@platform.test']);

        $acme = $this->register('Acme Ltd', 'owner@acme.test', 'pro');
        $this->context->run($acme->tenant, function () use ($acme): void {
            $this->subscriptions->renew(null);
            $this->addUser('Acme Admin', 'admin@acme.test', TenantRole::Admin);
            $this->addUser('Acme Member', 'member@acme.test', TenantRole::Member);
            $this->addCustomers($acme, 200);
            $this->addSnapshots(30);
        });

        $globex = $this->register('Globex', 'owner@globex.test', 'free');
        $this->context->run($globex->tenant, fn () => $this->addCustomers($globex, 40));

        $initech = $this->register('Initech', 'owner@initech.test', 'starter');
        $this->context->run($initech->tenant, fn () => $this->addCustomers($initech, 15));
    }

    private function register(string $company, string $email, string $planCode): User
    {
        $result = $this->tenants->register(new RegisterTenantData(
            companyName: $company,
            name: $company.' Owner',
            email: $email,
            password: self::PASSWORD,
            planCode: $planCode,
        ));

        return $result->user;
    }

    private function addUser(string $name, string $email, TenantRole $role): void
    {
        $user = $this->users->create(new CreateUserData($name, $email, $role));
        $this->userRepository->update($user, ['password' => self::PASSWORD]);
    }

    private function addCustomers(User $owner, int $count): void
    {
        foreach (range(1, $count) as $i) {
            $customer = $this->customers->create(new CreateCustomerData(
                name: fake()->name(),
                email: "customer{$i}@".str($owner->tenant->name)->slug().'.test',
                phone: fake()->numerify('+8801#########'),
                companyName: fake()->optional()->company(),
                status: $i % 10 === 0 ? CustomerStatus::Inactive : CustomerStatus::Active,
            ), $owner);

            $this->backdate($customer, CarbonImmutable::now()->subDays(($i * 7) % 90)->subMinutes($i));
        }
    }

    private function backdate(Customer $customer, CarbonImmutable $createdAt): void
    {
        $customer->timestamps = false;
        $customer->forceFill(['created_at' => $createdAt, 'updated_at' => $createdAt])->save();
    }

    private function addSnapshots(int $days): void
    {
        foreach (range($days, 1) as $daysAgo) {
            DailyUsageSnapshot::query()->create([
                'snapshot_date' => CarbonImmutable::today()->subDays($daysAgo),
                'users_count' => 3,
                'customers_count' => 200 - (int) round($daysAgo * 1.5),
            ]);
        }
    }
}
