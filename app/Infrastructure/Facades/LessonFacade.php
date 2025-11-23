<?php

namespace App\Infrastructure\Facades;

use App\Application\DTOs\Lesson\GetLessonsDTO;
use App\Application\Services\LessonService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * LessonFacade - Provides a simplified interface between Controller and Services
 * 
 * This facade acts as the entry point for all lesson-related operations,
 * coordinating between multiple services while keeping the controller thin.
 */
class LessonFacade
{
    public function __construct(
        private LessonService $lessonService,
    ) {
    }

    /**
     * Get lessons for student with filters
     * Returns paginated collection of lessons
     */
    public function getLessons(GetLessonsDTO $dto): LengthAwarePaginator
    {
        return $this->lessonService->getLessonsForStudent($dto);
    }
}

