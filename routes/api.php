<?php

use App\Presentation\Http\Controllers\Api\AuthController;
use App\Presentation\Http\Controllers\Api\ExamController;
use App\Presentation\Http\Controllers\Api\AssignmentController;
use App\Presentation\Http\Controllers\Api\UserActivityController;
use App\Presentation\Http\Controllers\Api\StudentController;
use App\Presentation\Http\Controllers\Api\AvatarController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::middleware('throttle:10,1')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/forget-password', [AuthController::class, 'forgetPassword']);
        Route::post('/confirm-otp', [AuthController::class, 'confirmOtp']);
        Route::post('/resend-otp', [AuthController::class, 'resendOtp']);
    });

    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);

    Route::post('/logout', [AuthController::class, 'logout'])->middleware('jwt.auth');
});

// Protected routes
Route::middleware(['jwt.auth', 'user.activity'])->group(function () {
    // Exams & Trainings
    Route::prefix('exams')->controller(ExamController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/{id}', 'show');
        Route::post('/{id}/start', 'start');
        Route::post('/submit-answer', 'submitAnswer');
        Route::post('/{id}/heartbeat', 'sendHeartbeat');
        Route::post('/{id}/submit', 'submitEntireExam');
    });

    // Assignments
    Route::prefix('assignments')->group(function () {
        Route::get('/', [AssignmentController::class, 'index']);
    });

    // User Activity Tracking
    Route::prefix('activity')->group(function () {
        Route::get('/summary', [UserActivityController::class, 'getActivitySummary']);
        Route::get('/total-time', [UserActivityController::class, 'getTotalTimeSpent']);
        Route::get('/daily', [UserActivityController::class, 'getDailyActivity']);
        Route::post('/end-session', [UserActivityController::class, 'endSession']);
        Route::get('/most-active-users', [UserActivityController::class, 'getMostActiveUsers']);
    });

    // Student Operations
    Route::prefix('student')->group(function () {
        Route::get('/profile', [StudentController::class, 'getProfile']);
    });

    // Avatar Operations
    Route::prefix('avatars')->group(function () {
        Route::get('/', [AvatarController::class, 'getAvatars']);
        Route::get('/owned', [AvatarController::class, 'getOwnedAvatars']);
        Route::post('/purchase', [AvatarController::class, 'purchaseAvatar']);
        Route::post('/set-active', [AvatarController::class, 'setActiveAvatar']);
    });
});