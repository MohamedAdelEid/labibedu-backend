<?php

namespace App\Domain\Interfaces\Repositories;

use Illuminate\Database\Eloquent\Collection;

interface JourneyRepositoryInterface
{
    /**
     * Get all journey levels with their stages ordered by order
     */
    public function getAllLevelsWithStages(): Collection;

    /**
     * Get student's progress for all stages
     */
    public function getStudentProgress(int $studentId): Collection;

    /**
     * Get or create student progress for a specific stage
     */
    public function getOrCreateStudentStageProgress(int $studentId, int $stageId): mixed;

    /**
     * Check if a book is completed by student (book + related training)
     */
    public function isBookCompleted(int $studentId, int $bookId): bool;

    /**
     * Check if a video is completed by student
     */
    public function isVideoCompleted(int $studentId, int $videoId): bool;

    /**
     * Check if an exam/training is completed by student
     */
    public function isExamTrainingCompleted(int $studentId, int $examTrainingId): bool;

    /**
     * Count completed contents for a student in a stage
     */
    public function countCompletedContents(int $studentId, int $stageId): int;
}

