<?php

namespace App\Infrastructure\Facades;

use App\Application\DTOs\Subject\GetSubjectsDTO;
use App\Application\Services\SubjectService;
use Illuminate\Support\Collection;

/**
 * SubjectFacade - Provides a simplified interface between Controller and Services
 */
class SubjectFacade
{
    public function __construct(
        private SubjectService $subjectService,
    ) {
    }

    /**
     * Get subjects by grade_id or user_id
     */
    public function getSubjects(GetSubjectsDTO $dto): Collection
    {
        return $this->subjectService->getSubjects($dto);
    }
}

