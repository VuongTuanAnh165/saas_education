<?php

namespace App\Services;

use App\Enums\TeacherStatus;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Exceptions\BusinessException;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class AdminTeacherService
{
    public function __construct(
        private readonly AuthService $authService,
        private readonly AuditLogService $auditLogService,
        private readonly TenantContextService $tenantContextService,
    ) {
    }

    /**
     * Admin creates a Teacher tenant (Sprint 0).
     *
     * - Creates Teacher user (role=teacher) and Teacher record in a transaction.
     * - Returns set-password URL (onboarding) instead of returning a temp password.
     * - Does not assign subscription/pricing in Sprint 0.
     *
     * @return array{teacher: Teacher, user: User, setPasswordUrl: string}
     */
    public function createTeacher(User $actor, string $email, string $displayName): array
    {
        $this->tenantContextService->assertActorCanAccessSystem($actor);

        if ($actor->role !== UserRole::ADMIN) {
            throw new BusinessException('FORBIDDEN', Response::HTTP_FORBIDDEN);
        }

        /** @var array{teacher: Teacher, user: User, setPasswordUrl: string} $created */
        $created = DB::transaction(function () use ($email, $displayName) {
            $exists = User::query()->where('email', $email)->exists();

            if ($exists) {
                throw new BusinessException('EMAIL_ALREADY_USED', Response::HTTP_CONFLICT);
            }

            $user = User::query()->create([
                'name' => $displayName,
                'email' => $email,
                // password will be set via setup token
                'password' => str()->random(32),
                'role' => UserRole::TEACHER,
                'status' => UserStatus::ACTIVE,
            ]);

            $teacher = Teacher::query()->create([
                'user_id' => $user->id,
                'display_name' => $displayName,
                'status' => TeacherStatus::ACTIVE,
            ]);

            $setPasswordUrl = $this->authService->createPasswordSetupLinkForUser($user);

            return ['teacher' => $teacher, 'user' => $user, 'setPasswordUrl' => $setPasswordUrl];
        });

        $this->auditLogService->write(
            actorUserId: $actor->id,
            teacherId: null,
            action: 'ADMIN_CREATE_TEACHER',
            targetType: Teacher::class,
            targetId: $created['teacher']->id,
            metadata: [
                'teacher_user_id' => $created['user']->id,
            ]
        );

        return $created;
    }

    /**
     * Admin locks a Teacher tenant.
     *
     * Sprint 0 decision: do NOT suspend teacher user's status.
     * Chain lock is enforced by checking teacher.status at runtime.
     */
    public function lockTeacher(User $actor, int $teacherId): void
    {
        $this->tenantContextService->assertActorCanAccessSystem($actor);

        if ($actor->role !== UserRole::ADMIN) {
            throw new BusinessException('FORBIDDEN', Response::HTTP_FORBIDDEN);
        }

        /** @var Teacher|null $teacher */
        $teacher = Teacher::query()->where('id', $teacherId)->first();

        if (!$teacher instanceof Teacher) {
            throw new BusinessException('NOT_FOUND', Response::HTTP_NOT_FOUND);
        }

        $teacher->forceFill(['status' => TeacherStatus::SUSPENDED])->save();

        $this->auditLogService->write(
            actorUserId: $actor->id,
            teacherId: null,
            action: 'ADMIN_LOCK_TEACHER',
            targetType: Teacher::class,
            targetId: $teacher->id,
        );
    }

    /**
     * Admin unlocks a Teacher tenant.
     */
    public function unlockTeacher(User $actor, int $teacherId): void
    {
        $this->tenantContextService->assertActorCanAccessSystem($actor);

        if ($actor->role !== UserRole::ADMIN) {
            throw new BusinessException('FORBIDDEN', Response::HTTP_FORBIDDEN);
        }

        /** @var Teacher|null $teacher */
        $teacher = Teacher::query()->where('id', $teacherId)->first();

        if (!$teacher instanceof Teacher) {
            throw new BusinessException('NOT_FOUND', Response::HTTP_NOT_FOUND);
        }

        $teacher->forceFill(['status' => TeacherStatus::ACTIVE])->save();

        $this->auditLogService->write(
            actorUserId: $actor->id,
            teacherId: null,
            action: 'ADMIN_UNLOCK_TEACHER',
            targetType: Teacher::class,
            targetId: $teacher->id,
        );
    }
}
