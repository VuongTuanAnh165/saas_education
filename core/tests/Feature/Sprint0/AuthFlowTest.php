<?php

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

it('can login with valid credentials and returns api envelope', function () {
    $user = User::query()->create([
        'name' => 'Teacher A',
        'email' => 'teacher_a@example.com',
        'password' => 'password123',
        'role' => UserRole::TEACHER,
        'status' => UserStatus::ACTIVE,
    ]);

    $resp = $this->postJson('/api/auth/login', [
        'email' => $user->email,
        'password' => 'password123',
    ]);

    $resp->assertStatus(Response::HTTP_OK);
    $resp->assertJson([
        'success' => true,
        'code' => 'AUTH_LOGIN_SUCCESS',
    ]);
    $resp->assertJsonStructure([
        'success',
        'code',
        'message',
        'data' => ['token', 'user'],
        'meta' => ['request_id', 'timestamp'],
    ]);
});

it('returns 401 for invalid credentials', function () {
    User::query()->create([
        'name' => 'Teacher A',
        'email' => 'teacher_a@example.com',
        'password' => 'password123',
        'role' => UserRole::TEACHER,
        'status' => UserStatus::ACTIVE,
    ]);

    $resp = $this->postJson('/api/auth/login', [
        'email' => 'teacher_a@example.com',
        'password' => 'wrong',
    ]);

    $resp->assertStatus(Response::HTTP_UNAUTHORIZED);
    $resp->assertJson([
        'success' => false,
        'code' => 'INVALID_CREDENTIALS',
    ]);
});

it('allows set password by setup token', function () {
    $user = User::query()->create([
        'name' => 'Teacher A',
        'email' => 'teacher_a@example.com',
        'password' => 'password123',
        'role' => UserRole::TEACHER,
        'status' => UserStatus::ACTIVE,
        'password_setup_token' => 'tok_123',
        'password_setup_token_expires_at' => now()->addMinutes(30),
    ]);

    $resp = $this->postJson('/api/auth/set-password', [
        'token' => 'tok_123',
        'password' => 'newpassword123',
        'password_confirmation' => 'newpassword123',
    ]);

    $resp->assertStatus(Response::HTTP_OK);
    $resp->assertJson([
        'success' => true,
        'code' => 'AUTH_PASSWORD_SET_SUCCESS',
    ]);

    $resp->assertJsonPath('data', []);

    $user->refresh();
    expect($user->password_setup_token)->toBeNull();
});
