<?php

namespace App\Presentation\Http\Controllers\Api;

use App\Application\DTOs\Lesson\GetLessonsDTO;
use App\Infrastructure\Facades\LessonFacade;
use App\Infrastructure\Helpers\ApiResponse;
use App\Presentation\Http\Requests\Lesson\GetLessonsRequest;
use App\Presentation\Http\Resources\Lesson\LessonResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class LessonController extends Controller
{
    public function __construct(
        private LessonFacade $lessonFacade
    ) {
    }

    /**
     * Get lessons for student by subject with filters and pagination
     */
    public function index(GetLessonsRequest $request): JsonResponse
    {
        $studentId = auth()->user()->student->id;

        $dto = GetLessonsDTO::fromRequest($request->validated(), $studentId);

        // Get paginated lessons from facade
        $lessons = $this->lessonFacade->getLessons($dto);

        // Preload attempts for all trainings to avoid N+1 queries
        $trainIds = collect($lessons->items())->pluck('train_id')->filter()->unique()->values()->toArray();

        if (!empty($trainIds)) {
            // Get all attempts for these trainings, ordered by id desc to get latest first
            $allAttempts = \App\Infrastructure\Models\ExamAttempt::where('student_id', $studentId)
                ->whereIn('exam_training_id', $trainIds)
                ->orderBy('id', 'desc')
                ->get();

            // Group by exam_training_id and get the first (latest) attempt for each
            $attempts = $allAttempts
                ->groupBy('exam_training_id')
                ->map(function ($group) {
                    // First item is the latest (because we ordered by id desc)
                    return $group->first();
                })
                ->filter() // Remove any null values
                ->toArray(); // Convert to array with proper keys

            // Set attempts cache in resource (keyed by exam_training_id)
            LessonResource::setAttemptsCache($attempts);
        }

        // Transform lessons using resource
        $lessonsCollection = LessonResource::collection($lessons->items());

        // Reset cache after processing
        LessonResource::resetCache();

        // Return paginated response
        return ApiResponse::success([
            'data' => $lessonsCollection,
            'currentPage' => $lessons->currentPage(),
            'totalPages' => $lessons->lastPage(),
            'pageSize' => $lessons->perPage(),
            'totalRecords' => $lessons->total(),
        ]);
    }
}

