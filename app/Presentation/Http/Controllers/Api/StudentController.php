<?php

namespace App\Presentation\Http\Controllers\Api;

use App\Domain\Interfaces\Services\StudentServiceInterface;
use App\Presentation\Http\Controllers\Controller;
use App\Presentation\Http\Resources\Student\StudentProfileResource;
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
}
