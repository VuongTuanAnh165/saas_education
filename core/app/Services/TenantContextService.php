<?php

namespace App\Services;

use App\Enums\TeacherStatus;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Exceptions\BusinessException;
use App\Exceptions\SystemException;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class TenantContextService
{
    /**
     * Resolve tenant context from the authenticated user.
     *
     * Invariant (Sprint 0): tenant boundary is Teacher.
     * - Admin: not bound to a tenant.
     * - Teacher: resolved by teachers.user_id.
     * - Student: resolved by students.user_id -> teacher_id.
     *
     * Note: teacher_id MUST NOT come from client request.
     *
     * @return array{teacherId: int|null, teacher: Teacher|null}
     */
    public function resolveTenant(User $actor): array
    {
        if ($actor->role === UserRole::ADMIN) {
            return ['teacherId' => null, 'teacher' => null];
        }

        if ($actor->role === UserRole::TEACHER) {
            $teacher = Teacher::query()->where('user_id', $actor->id)->first();

            if (!$teacher instanceof Teacher) {
                throw new SystemException('DATA_INTEGRITY_ERROR');
            }

            return ['teacherId' => $teacher->id, 'teacher' => $teacher];
        }

        $student = Student::query()->where('user_id', $actor->id)->first();

        if (!$student instanceof Student) {
            throw new SystemException('DATA_INTEGRITY_ERROR');
        }

        $teacher = Teacher::query()->where('id', $student->teacher_id)->first();

        if (!$teacher instanceof Teacher) {
            throw new SystemException('DATA_INTEGRITY_ERROR');
        }

        return ['teacherId' => $teacher->id, 'teacher' => $teacher];
    }

    /**
     * Enforce access rules for the whole system (Sprint 0).
     *
     * - User must be active.
     * - Teacher/Student must have an active teacher (chain lock).
     *
     * When teacher is suspended, both Teacher and its Students are blocked.
     */
    public function assertActorCanAccessSystem(User $actor): void
    {
        if ($actor->status !== UserStatus::ACTIVE) {
            throw new BusinessException('USER_SUSPENDED', Response::HTTP_FORBIDDEN);
        }

        if ($actor->role === UserRole::ADMIN) {
            return;
        }

        $resolved = $this->resolveTenant($actor);
        $teacher = $resolved['teacher'];

        if (!$teacher instanceof Teacher) {
            throw new SystemException('DATA_INTEGRITY_ERROR');
        }

        if ($teacher->status !== TeacherStatus::ACTIVE) {
            throw new BusinessException('TEACHER_SUSPENDED', Response::HTTP_FORBIDDEN);
        }
    }
}
