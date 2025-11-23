<?php

namespace App\Application\Services;

use App\Application\DTOs\Lesson\GetLessonsDTO;
use App\Domain\Interfaces\Repositories\LessonRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LessonService
{
    public function __construct(
        private LessonRepositoryInterface $lessonRepository,
    ) {
    }

    /**
     * Get lessons for student by subject with filters
     * Returns paginated collection of lessons
     */
    public function getLessonsForStudent(GetLessonsDTO $dto): LengthAwarePaginator
    {
        return $this->lessonRepository->getLessonsForStudent(
            $dto->studentId,
            $dto->subjectId,
            $dto->search,
            $dto->perPage
        );
    }
}

