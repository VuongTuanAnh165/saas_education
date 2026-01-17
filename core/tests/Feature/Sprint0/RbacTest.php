<?php

use App\Enums\TeacherStatus;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

it('teacher cannot access admin routes', function () {
    $teacherUser = User::query()->create([
        'name' => 'Teacher A',
        'email' => 'teacher_a@example.com',
        'password' => 'password123',
        'role' => UserRole::TEACHER,
        'status' => UserStatus::ACTIVE,
    ]);

    Teacher::query()->create([
        'user_id' => $teacherUser->id,
        'display_name' => 'Teacher A',
        'status' => TeacherStatus::ACTIVE,
    ]);

    $token = $teacherUser->createToken('api')->plainTextToken;

    $resp = $this->withHeader('Authorization', 'Bearer '.$token)
        ->postJson('/api/admin/teachers', [
            'email' => 't2@example.com',
            'display_name' => 'Teacher 2',
        ]);

    $resp->assertStatus(Response::HTTP_FORBIDDEN);
    $resp->assertJson([
        'success' => false,
        'code' => 'FORBIDDEN',
    ]);

    $resp->assertJsonPath('data', []);
});

it('student cannot access teacher routes', function () {
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

    $token = $studentUser->createToken('api')->plainTextToken;

    $resp = $this->withHeader('Authorization', 'Bearer '.$token)
        ->postJson('/api/teacher/students', [
            'full_name' => 'Student Y',
            'email' => 'student_y@example.com',
        ]);

    $resp->assertStatus(Response::HTTP_FORBIDDEN);
    $resp->assertJson([
        'success' => false,
        'code' => 'FORBIDDEN',
    ]);

    $resp->assertJsonPath('data', []);
});
