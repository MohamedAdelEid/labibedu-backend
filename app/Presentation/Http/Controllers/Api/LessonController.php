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

        // Transform lessons using resource
        $lessonsCollection = LessonResource::collection($lessons->items());

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

