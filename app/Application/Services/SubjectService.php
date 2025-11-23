<?php

namespace App\Application\Services;

use App\Application\DTOs\Subject\GetSubjectsDTO;
use App\Domain\Interfaces\Repositories\SubjectRepositoryInterface;
use Illuminate\Support\Collection;

class SubjectService
{
    public function __construct(
        private SubjectRepositoryInterface $subjectRepository,
    ) {
    }

    /**
     * Get subjects by grade_id or user_id
     */
    public function getSubjects(GetSubjectsDTO $dto): Collection
    {
        if ($dto->gradeId) {
            return $this->subjectRepository->getSubjectsByGradeId($dto->gradeId);
        }
        
        if ($dto->userId) {
            return $this->subjectRepository->getSubjectsByUserId($dto->userId);
        }
        
        return collect();
    }
}

