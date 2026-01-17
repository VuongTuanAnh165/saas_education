<?php

use App\Http\Controllers\Api\AdminTeacherController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\SetPasswordController;
use App\Http\Controllers\Api\StudentDashboardController;
use App\Http\Controllers\Api\TeacherStudentController;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('set-password', SetPasswordController::class);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

Route::middleware(['auth:sanctum', 'tenant.context', 'enforce.status'])->group(function () {
    Route::prefix('admin')->middleware('require.role:'.UserRole::ADMIN->value)->group(function () {
        Route::post('teachers', [AdminTeacherController::class, 'store']);
        Route::post('teachers/{teacherId}/lock', [AdminTeacherController::class, 'lock']);
        Route::post('teachers/{teacherId}/unlock', [AdminTeacherController::class, 'unlock']);
    });

    Route::prefix('teacher')->middleware('require.role:'.UserRole::TEACHER->value)->group(function () {
        Route::post('students', [TeacherStudentController::class, 'store']);
    });

    Route::prefix('student')->middleware('require.role:'.UserRole::STUDENT->value)->group(function () {
        Route::get('dashboard', StudentDashboardController::class);
    });
});
