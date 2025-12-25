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
            $attempts = \App\Infrastructure\Models\ExamAttempt::where('student_id', $studentId)
                ->whereIn('exam_training_id', $trainIds)
                ->orderBy('exam_training_id')
                ->orderBy('created_at', 'desc')
                ->get()
                ->groupBy('exam_training_id')
                ->map(function ($group) {
                    return $group->first(); // Get latest attempt for each training
                })
                ->keyBy('exam_training_id'); // Key by exam_training_id for easy lookup
            
            // Set attempts cache in resource
            LessonResource::setAttemptsCache($attempts->all());
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

