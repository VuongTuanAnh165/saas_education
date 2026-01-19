<?php

use App\Enums\TeacherStatus;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;

it('blocks student dashboard access when teacher is locked (runtime enforcement)', function () {
    $admin = User::query()->create([
        'name' => 'Admin',
        'email' => 'admin@example.com',
        'password' => 'password123',
        'role' => UserRole::ADMIN,
        'status' => UserStatus::ACTIVE,
    ]);

    $teacherUser = User::query()->create([
        'name' => 'Teacher A',
        'email' => 'teacher_a@example.com',
        'password' => 'password123',
        'role' => UserRole::TEACHER,
        'status' => UserStatus::ACTIVE,
    ]);

    $teacher = Teacher::query()->create([
        'user_id' => $teacherUser->id,
        'display_name' => 'Teacher A',
        'status' => TeacherStatus::ACTIVE,
    ]);

    $studentUser = User::query()->create([
        'name' => 'Student X',
        'email' => 'student_x@example.com',
        'password' => 'password123',
        'role' => UserRole::STUDENT,
        'status' => UserStatus::ACTIVE,
    ]);

    Student::query()->create([
        'user_id' => $studentUser->id,
        'teacher_id' => $teacher->id,
        'full_name' => 'Student X',
    ]);

    Sanctum::actingAs($studentUser);

    $okResp = $this->getJson('/api/student/dashboard');

    $okResp->assertStatus(Response::HTTP_OK);
    $okResp->assertJson([
        'success' => true,
        'code' => 'STUDENT_DASHBOARD_LOADED',
    ]);

    Sanctum::actingAs($admin);

    $lockResp = $this->postJson('/api/admin/teachers/'.$teacher->id.'/lock');

    $lockResp->assertStatus(Response::HTTP_OK);

    Sanctum::actingAs($studentUser);

    $dashboardResp = $this->getJson('/api/student/dashboard');

    $dashboardResp->assertStatus(Response::HTTP_FORBIDDEN);
    $dashboardResp->assertJson([
        'success' => false,
        'code' => 'TEACHER_SUSPENDED',
    ]);
    $dashboardResp->assertJsonPath('data', []);
});

