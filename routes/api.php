<?php

use App\Presentation\Http\Controllers\Api\AuthController;
use App\Presentation\Http\Controllers\Api\ExamController;
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
Route::middleware(['jwt.auth'])->group(function () {
    // Exams & Trainings
    Route::prefix('exams')->group(function () {
        Route::get('/', [ExamController::class, 'index']);
        Route::get('/{id}', [ExamController::class, 'show']);
        Route::post('/answer', [ExamController::class, 'submitAnswer']);
        Route::post('/submit-entire', [ExamController::class, 'submitEntireExam']);
    });
});