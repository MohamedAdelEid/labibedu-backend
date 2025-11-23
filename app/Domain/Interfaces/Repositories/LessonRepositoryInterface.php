<?php

namespace App\Domain\Interfaces\Repositories;

use App\Infrastructure\Models\Lesson;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface LessonRepositoryInterface
{
    /**
     * Get lessons for a student by subject with filters
     */
    public function getLessonsForStudent(
        int $studentId,
        int $subjectId,
        ?string $search,
        int $perPage
    ): LengthAwarePaginator;
}

