<?php

namespace Database\Seeders;

use App\Enums\TeacherStatus;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\AuditLog;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\Seeder;

class Sprint0Seeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::query()->firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Admin',
                'password' => 'password123',
                'role' => UserRole::ADMIN,
                'status' => UserStatus::ACTIVE,
            ]
        );

        // Teachers + Students
        $teachers = Teacher::factory()
            ->count(3)
            ->create([
                'status' => TeacherStatus::ACTIVE,
            ])
            ->each(function (Teacher $teacher) {
                // Ensure teacher user role/status
                $teacher->user()->update([
                    'role' => UserRole::TEACHER,
                    'status' => UserStatus::ACTIVE,
                ]);

                // Students for each teacher
                Student::factory()
                    ->count(5)
                    ->create([
                        'teacher_id' => $teacher->id,
                    ])
                    ->each(function (Student $student) {
                        $student->user()->update([
                            'role' => UserRole::STUDENT,
                            'status' => UserStatus::ACTIVE,
                        ]);
                    });
            });

        // Sample audit logs
        foreach ($teachers as $teacher) {
            AuditLog::query()->create([
                'actor_user_id' => $admin->id,
                'teacher_id' => null,
                'action' => 'SEED_CREATE_TEACHER',
                'target_type' => Teacher::class,
                'target_id' => $teacher->id,
                'metadata' => [
                    'seed' => true,
                ],
            ]);
        }
    }
}
