<?php

namespace App\Services;

use App\Enums\UserStatus;
use App\Exceptions\BusinessException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Symfony\Component\HttpFoundation\Response;

class AuthService
{
    public function __construct(
        private readonly TenantContextService $tenantContextService,
        private readonly AuditLogService $auditLogService,
    ) {
    }

    /**
     * Login and issue a Sanctum token (Sprint 0).
     *
     * Rules:
     * - Validate credentials.
     * - Enforce user/teacher status (chain lock) via TenantContextService.
     * - Return token + minimal user profile.
     *
     * @return array{token: string, user: array<string, mixed>}
     */
    public function login(string $email, string $password): array
    {
        /** @var User|null $user */
        $user = User::query()->where('email', $email)->first();

        if (!$user instanceof User) {
            throw new BusinessException('INVALID_CREDENTIALS', Response::HTTP_UNAUTHORIZED);
        }

        if (!Hash::check($password, $user->password)) {
            throw new BusinessException('INVALID_CREDENTIALS', Response::HTTP_UNAUTHORIZED);
        }

        $this->tenantContextService->assertActorCanAccessSystem($user);

        $token = $user->createToken('api')->plainTextToken;

        $resolved = $this->tenantContextService->resolveTenant($user);

        $this->auditLogService->write(
            actorUserId: $user->id,
            teacherId: $resolved['teacherId'],
            action: 'AUTH_LOGIN',
        );

        return [
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'role' => $user->role->value,
                'status' => $user->status->value,
            ],
        ];
    }

    /**
     * Revoke the current Sanctum token (Sprint 0).
     */
    public function logout(User $actor): void
    {
        $token = $actor->currentAccessToken();

        if ($token === null) {
            return;
        }

        $token->delete();

        $resolved = $this->tenantContextService->resolveTenant($actor);

        $this->auditLogService->write(
            actorUserId: $actor->id,
            teacherId: $resolved['teacherId'],
            action: 'AUTH_LOGOUT',
        );
    }

    /**
     * Forgot password (Sprint 0, Option B).
     *
     * - Do not return reset URL.
     * - Trigger Laravel's reset-link flow (mail may be configured later).
     * - Always behave safely: if user is not found, just return.
     */
    public function forgotPassword(string $email): void
    {
        /** @var User|null $user */
        $user = User::query()->where('email', $email)->first();

        if (!$user instanceof User) {
            return;
        }

        Password::sendResetLink(['email' => $email]);

        $resolved = $this->tenantContextService->resolveTenant($user);

        $this->auditLogService->write(
            actorUserId: $user->id,
            teacherId: $resolved['teacherId'],
            action: 'AUTH_FORGOT_PASSWORD',
        );
    }

    /**
     * Create a set-password link for onboarding.
     *
     * Used by Admin create teacher (Sprint 0). Token is stored on users table.
     */
    public function createPasswordSetupLinkForUser(User $targetUser, int $expiresInMinutes = 60): string
    {
        $token = bin2hex(random_bytes(32));

        $targetUser->forceFill([
            'password_setup_token' => $token,
            'password_setup_token_expires_at' => now()->addMinutes($expiresInMinutes),
        ])->save();

        $clientUrl = rtrim((string) config('client.url'), '/');

        return $clientUrl.'/set-password?token='.$token;
    }

    /**
     * Set password by setup token (onboarding).
     */
    public function setPasswordBySetupToken(string $token, string $password): void
    {
        /** @var User|null $user */
        $user = User::query()
            ->where('password_setup_token', $token)
            ->first();

        if (!$user instanceof User) {
            throw new BusinessException('INVALID_TOKEN', Response::HTTP_BAD_REQUEST);
        }

        if ($user->password_setup_token_expires_at === null || now()->greaterThan($user->password_setup_token_expires_at)) {
            throw new BusinessException('TOKEN_EXPIRED', Response::HTTP_BAD_REQUEST);
        }

        if ($user->status !== UserStatus::ACTIVE) {
            throw new BusinessException('USER_SUSPENDED', Response::HTTP_FORBIDDEN);
        }

        $user->forceFill([
            'password' => $password,
            'password_setup_token' => null,
            'password_setup_token_expires_at' => null,
        ])->save();

        $teacherId = null;

        try {
            $resolved = $this->tenantContextService->resolveTenant($user);
            $teacherId = $resolved['teacherId'];
        } catch (\Throwable) {
            // ignore
        }

        $this->auditLogService->write(
            actorUserId: $user->id,
            teacherId: $teacherId,
            action: 'AUTH_SET_PASSWORD',
        );
    }

    /**
     * Convenience guard for protected endpoints.
     */
    public function ensureActive(User $user): void
    {
        if ($user->status !== UserStatus::ACTIVE) {
            throw new BusinessException('USER_SUSPENDED', Response::HTTP_FORBIDDEN);
        }

        $this->tenantContextService->assertActorCanAccessSystem($user);
    }
}
