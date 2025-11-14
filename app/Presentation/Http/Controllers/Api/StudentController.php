<?php

namespace App\Presentation\Http\Controllers\Api;

use App\Application\DTOs\Student\FirstSetupDTO;
use App\Application\DTOs\Student\UpdateSettingsDTO;
use App\Domain\Interfaces\Services\StudentServiceInterface;
use App\Presentation\Http\Controllers\Controller;
use App\Presentation\Http\Resources\Student\StudentProfileResource;
use App\Presentation\Http\Resources\Student\StudentSettingsResource;
use App\Presentation\Http\Requests\Student\FirstSetupRequest;
use App\Presentation\Http\Requests\Student\UpdateSettingsRequest;
use App\Infrastructure\Helpers\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function __construct(
        private StudentServiceInterface $studentService
    ) {
    }

    /**
     * Get student profile
     */
    public function getProfile(): JsonResponse
    {
        $user = Auth::user();

        try {
            $profileData = $this->studentService->getStudentProfile($user->student->id);

            return ApiResponse::success(
                new StudentProfileResource($profileData),
                'Student profile retrieved successfully'
            );
        } catch (\Exception $e) {
            return ApiResponse::error(
                $e->getMessage(),
                null,
                404
            );
        }
    }

    /**
     * Complete first-time student setup
     */
    public function firstSetup(FirstSetupRequest $request): JsonResponse
    {
        $dto = FirstSetupDTO::fromRequest($request->validated());
        $studentId = Auth::user()->student->id;

        $result = $this->studentService->completeFirstSetup($studentId, [
            'name' => $dto->name,
            'age_group_id' => $dto->ageGroupId,
            'gender' => $dto->gender,
        ]);

        return ApiResponse::success(
            new StudentSettingsResource($result['student']),
            $result['message']
        );
    }

    /**
     * Get student settings
     */
    public function getSettings(): JsonResponse
    {
        $studentId = Auth::user()->student->id;

        $settings = $this->studentService->getSettings($studentId);

        return ApiResponse::success(
            $settings,
            'Settings retrieved successfully.'
        );
    }

    /**
     * Update student settings
     */
    public function updateSettings(UpdateSettingsRequest $request): JsonResponse
    {
        $dto = UpdateSettingsDTO::fromRequest($request->validated());
        $studentId = Auth::user()->student->id;

        $result = $this->studentService->updateSettings($studentId, [
            'language' => $dto->language,
            'theme' => $dto->theme,
            'notifications_enabled' => $dto->notificationsEnabled,
            'haptic_feedback_enabled' => $dto->hapticFeedbackEnabled,
        ]);

        return ApiResponse::success(
            new StudentSettingsResource($result['student']),
            $result['message']
        );
    }

    /**
     * Complete first-time setup (mark is_first_time as false)
     */
    public function completeFirstTime(): JsonResponse
    {
        $studentId = Auth::user()->student->id;

        $result = $this->studentService->completeFirstTime($studentId);

        return ApiResponse::success(
            $result,
            'First-time setup completed successfully'
        );
    }
}
