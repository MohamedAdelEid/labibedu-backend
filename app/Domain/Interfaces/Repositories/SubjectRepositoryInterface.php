<?php

namespace App\Domain\Interfaces\Repositories;

use Illuminate\Support\Collection;

interface SubjectRepositoryInterface
{
    /**
     * Get subjects by grade_id
     */
    public function getSubjectsByGradeId(int $gradeId): Collection;

    /**
     * Get subjects by user_id (through student's grade)
     */
    public function getSubjectsByUserId(int $userId): Collection;
}

