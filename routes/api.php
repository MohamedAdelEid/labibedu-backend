<?php

use App\Presentation\Http\Controllers\Api\AuthController;
use App\Presentation\Http\Controllers\Api\ExamController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('jwt.auth');
    Route::post('/forget-password', [AuthController::class, 'forgetPassword'])->middleware('throttle:3,1');
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    Route::post('/confirm-otp', [AuthController::class, 'confirmOtp'])->middleware('throttle:5,1');
    Route::post('/resend-otp', [AuthController::class, 'resendOtp'])->middleware('throttle:3,1');
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