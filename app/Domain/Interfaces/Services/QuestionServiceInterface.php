<?php

namespace App\Domain\Interfaces\Services;

interface QuestionServiceInterface
{
    /**
     * Get question statistics for a student
     */
    public function getStudentQuestionStatistics(int $studentId): array;
}
