<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Exceptions\BusinessException;
use App\Exceptions\SystemException;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TeacherStudentService
{
    public function __construct(
        private readonly AuthService $authService,
        private readonly AuditLogService $auditLogService,
        private readonly TenantContextService $tenantContextService,
    ) {
    }

    /**
     * @return array{student: Student, user: User, setPasswordUrl: string|null}
     */
    public function createStudent(User $actor, string $fullName, ?string $email): array
    {
        $this->tenantContextService->assertActorCanAccessSystem($actor);

        if ($actor->role !== UserRole::TEACHER) {
            throw new BusinessException('FORBIDDEN', Response::HTTP_FORBIDDEN);
        }

        $resolved = $this->tenantContextService->resolveTenant($actor);
        $teacherId = $resolved['teacherId'];

        if ($teacherId === null) {
            throw new SystemException('DATA_INTEGRITY_ERROR');
        }

        /** @var array{student: Student, user: User, setPasswordUrl: string|null} $created */
        $created = DB::transaction(function () use ($teacherId, $fullName, $email) {
            if ($email !== null) {
                $exists = User::query()->where('email', $email)->exists();
                if ($exists) {
                    throw new BusinessException('EMAIL_ALREADY_USED', Response::HTTP_CONFLICT);
                }
            }

            $user = User::query()->create([
                'name' => $fullName,
                'email' => $email ?? sprintf('student-%s@placeholder.local', str()->uuid()),
                'password' => str()->random(32),
                'role' => UserRole::STUDENT,
                'status' => UserStatus::ACTIVE,
            ]);

            $student = Student::query()->create([
                'user_id' => $user->id,
                'teacher_id' => $teacherId,
                'full_name' => $fullName,
            ]);

            $setPasswordUrl = null;
            if ($email !== null) {
                $setPasswordUrl = $this->authService->createPasswordSetupLinkForUser($user);
            }

            return ['student' => $student, 'user' => $user, 'setPasswordUrl' => $setPasswordUrl];
        });

        $this->auditLogService->write(
            actorUserId: $actor->id,
            teacherId: $teacherId,
            action: 'TEACHER_CREATE_STUDENT',
            targetType: Student::class,
            targetId: $created['student']->id,
            metadata: [
                'student_user_id' => $created['user']->id,
            ]
        );

        return $created;
    }
}
