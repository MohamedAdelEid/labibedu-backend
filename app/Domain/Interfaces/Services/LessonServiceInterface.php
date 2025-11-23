<?php

namespace App\Domain\Interfaces\Services;

use App\Application\DTOs\Lesson\GetLessonsDTO;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface LessonServiceInterface
{
    /**
     * Get lessons for student by subject with filters
     *
     * @param GetLessonsDTO $dto
     * @return LengthAwarePaginator
     */
    public function getLessonsForStudent(GetLessonsDTO $dto): LengthAwarePaginator;
}

