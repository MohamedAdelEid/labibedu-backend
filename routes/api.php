<?php

use App\Presentation\Http\Controllers\Api\AuthController;
use App\Presentation\Http\Controllers\Api\ExamController;
use App\Presentation\Http\Controllers\Api\AssignmentController;
use App\Presentation\Http\Controllers\Api\UserActivityController;
use App\Presentation\Http\Controllers\Api\StudentController;
use App\Presentation\Http\Controllers\Api\AvatarController;
use App\Presentation\Http\Controllers\Api\AvatarCategoryController;
use App\Presentation\Http\Controllers\Api\LibraryController;
use App\Presentation\Http\Controllers\Api\LevelController;
use App\Presentation\Http\Controllers\Api\JourneyController;
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

Route::middleware(['jwt.auth', 'user.activity'])->group(function () {

    // Student routes - Only students can access
    Route::middleware('authorize:student')->group(function () {
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
        });

        // Student Operations
        Route::prefix('student')->group(function () {
            Route::get('/profile', [StudentController::class, 'getProfile']);

            // Library
            Route::get('/library', [LibraryController::class, 'index']);

            // Book Pages
            Route::get('/books/{id}/pages', [LibraryController::class, 'getBookPages']);

            // Journey
            Route::get('/journey', [JourneyController::class, 'index']);
        });

        // Book Operations
        Route::prefix('books')->group(function () {
            Route::post('/{id}/favorite', [LibraryController::class, 'toggleFavorite']);
            Route::post('/{id}/open', [LibraryController::class, 'openBook']);
        });

        // Avatar Operations
        Route::prefix('avatars')->group(function () {
            Route::get('/student', [AvatarController::class, 'getAvatarsForStudent']);
            Route::get('/owned', [AvatarController::class, 'getOwnedAvatars']);
            Route::get('/category/{categoryId}', [AvatarController::class, 'getAvatarsByCategory']);
            Route::get('/grouped-by-category', [AvatarController::class, 'getAvatarsGroupedByCategory']);
            Route::post('/purchase', [AvatarController::class, 'purchaseAvatar']);
            Route::post('/set-active', [AvatarController::class, 'setActiveAvatar']);
        });
    });

    // Admin/Teacher routes - Admin and teachers can access
    Route::middleware('authorize:admin,teacher')->group(function () {
        // Avatar Management
        Route::prefix('avatars')->group(function () {
            Route::post('/create', [AvatarController::class, 'createAvatar']);
        });

        // Avatar Categories Management
        Route::prefix('avatar-categories')->controller(AvatarCategoryController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('/with-count', 'withAvatarCount');
            Route::post('/', 'store');
            Route::get('/{id}', 'show');
            Route::put('/{id}', 'update');
            Route::delete('/{id}', 'destroy');
        });

        // User Activity Analytics
        Route::prefix('activity')->group(function () {
            Route::get('/most-active-users', [UserActivityController::class, 'getMostActiveUsers']);
        });
    });

    Route::middleware('authorize:admin')->group(function () {
        // Add admin-specific routes here in the future
    });
});

// Public routes - Public routes are accessible without authentication
Route::prefix('avatars')->group(function () {
    Route::get('/', [AvatarController::class, 'getAvatars']);
});

Route::prefix('avatar-categories')->controller(AvatarCategoryController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/with-count', 'withAvatarCount');
});

// Levels (public)
Route::get('/levels', [LevelController::class, 'index']);