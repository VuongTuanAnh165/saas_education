<?php

use App\Enums\TeacherStatus;
use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\Teacher;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

it('locks teacher and blocks teacher login with TEACHER_SUSPENDED', function () {
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

    $adminToken = $admin->createToken('api')->plainTextToken;

    $lockResp = $this->withHeader('Authorization', 'Bearer '.$adminToken)
        ->postJson('/api/admin/teachers/'.$teacher->id.'/lock');

    $lockResp->assertStatus(Response::HTTP_OK);
    $lockResp->assertJson([
        'success' => true,
        'code' => 'ADMIN_TEACHER_LOCKED',
    ]);

    $lockResp->assertJsonPath('data', []);

    $teacher->refresh();
    expect($teacher->status->value)->toBe('suspended');

    $loginResp = $this->postJson('/api/auth/login', [
        'email' => 'teacher_a@example.com',
        'password' => 'password123',
    ]);

    $loginResp->assertStatus(Response::HTTP_FORBIDDEN);
    $loginResp->assertJson([
        'success' => false,
        'code' => 'TEACHER_SUSPENDED',
    ]);
});
