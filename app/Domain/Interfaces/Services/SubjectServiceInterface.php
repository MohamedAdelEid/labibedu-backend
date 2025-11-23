<?php

namespace App\Domain\Interfaces\Services;

use App\Application\DTOs\Subject\GetSubjectsDTO;
use Illuminate\Support\Collection;

interface SubjectServiceInterface
{
    /**
     * Get subjects by grade_id or user_id
     *
     * @param GetSubjectsDTO $dto
     * @return Collection
     */
    public function getSubjects(GetSubjectsDTO $dto): Collection;
}

