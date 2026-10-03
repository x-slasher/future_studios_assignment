<?php

declare(strict_types=1);

namespace App\Services;

use App\DTOs\LoginData;
use App\DTOs\ResetPasswordData;
use App\Enums\Permission;
use App\Enums\TenantRole;
use App\Enums\TenantStatus;
use App\Exceptions\InvalidCredentials;
use App\Exceptions\TenantSuspended;
use App\Mail\SetPasswordMail;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Support\Tenancy\TenantContext;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

use function Illuminate\Support\defer;

final class AuthService
{
    // Checked when no user matches, so login time does not reveal which emails exist.
    private const string DUMMY_HASH = '$2y$12$IiSJ8vakKT4yIxHTOlpxK.QlGAhtnzE0FaYnYbdwuM6biiadOztSG';

    public function __construct(
        private readonly UserRepositoryInterface $users,
        private readonly TenantContext $context,
    ) {}

    /** @return array{user: User, token: string} */
    public function login(LoginData $data): array
    {
        $user = $this->users->findActiveByEmail($data->email);
        $passwordMatches = Hash::check($data->password, $user?->password ?? self::DUMMY_HASH);

        if ($user === null || ! $passwordMatches) {
            throw new InvalidCredentials;
        }

        if ($user->tenant?->status === TenantStatus::Suspended) {
            throw new TenantSuspended;
        }

        $this->users->update($user, ['last_login_at' => CarbonImmutable::now()]);

        return ['user' => $this->profile($user), 'token' => $user->createToken('api')->plainTextToken];
    }

    public function logout(User $user): void
    {
        $this->users->revokeCurrentToken($user);
    }

    /** @return array{user: User, permissions: list<string>} */
    public function me(User $user): array
    {
        $user = $this->users->loadProfile($user);
        $role = $user->roles->first();

        $permissions = $role === null ? [] : TenantRole::from($role->name)->permissions();

        return ['user' => $user, 'permissions' => array_map(fn (Permission $p): string => $p->value, $permissions)];
    }

    public function requestPasswordReset(string $email): void
    {
        // After the response, so known and unknown emails answer equally fast.
        defer(fn () => $this->sendResetLink($email));
    }

    public function sendResetLink(string $email): void
    {
        Password::broker()->sendResetLink(['email' => $email], function (User $user, string $token): void {
            Mail::to($user)->queue((new SetPasswordMail($user->name, $user->email, $token))->onQueue('high'));
        });
    }

    public function resetPassword(ResetPasswordData $data): void
    {
        $status = Password::broker()->reset(
            ['email' => $data->email, 'token' => $data->token, 'password' => $data->password],
            function (User $user, string $password): void {
                $this->users->update($user, ['password' => $password]);
                $this->users->revokeTokens($user);
            },
        );

        if ($status !== PasswordBroker::PASSWORD_RESET) {
            throw ValidationException::withMessages(['token' => [__($status)]]);
        }
    }

    private function profile(User $user): User
    {
        if ($user->tenant === null) {
            return $this->users->loadProfile($user);
        }

        return $this->context->run($user->tenant, fn (): User => $this->users->loadProfile($user));
    }
}
