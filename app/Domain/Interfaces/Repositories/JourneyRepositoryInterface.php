<?php

namespace App\Domain\Interfaces\Repositories;

use Illuminate\Database\Eloquent\Collection;
use App\Infrastructure\Models\StudentStageProgress;

interface JourneyRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Get all journey levels with their stages ordered by order
     */
    public function getAllLevelsWithStages(): Collection;

    /**
     * Get student's progress records for all stages
     */
    public function getStudentProgressRecords(int $studentId): Collection;

    /**
     * Get student progress for a specific stage (read-only, returns null if not found)
     */
    public function getStudentStageProgress(int $studentId, int $stageId): ?StudentStageProgress;

    /**
     * Get all student stage progress records by stage IDs
     */
    public function getStudentProgressByStageIds(int $studentId, array $stageIds): Collection;

    /**
     * Get current stage for student (status = in_progress or not_started)
     */
    public function getCurrentStage(int $studentId): ?StudentStageProgress;

    /**
     * Initialize first stage for student if no progress exists
     */
    public function initializeFirstStage(int $studentId, int $firstStageId): StudentStageProgress;
}
