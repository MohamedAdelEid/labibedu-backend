<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Interfaces\Repositories\JourneyRepositoryInterface;
use App\Infrastructure\Models\JourneyLevel;
use App\Infrastructure\Models\StudentStageProgress;
use Illuminate\Database\Eloquent\Collection;

class JourneyRepository extends BaseRepository implements JourneyRepositoryInterface
{
    public function __construct(JourneyLevel $model)
    {
        parent::__construct($model);
    }

    /**
     * Get all journey levels with their stages ordered by order
     * Data access only - no business logic
     */
    public function getAllLevelsWithStages(): Collection
    {
        return JourneyLevel::with(['stages.contents'])
            ->orderBy('order')
            ->get();
    }

    /**
     * Get student's progress records for all stages
     * Read-only - returns existing records
     */
    public function getStudentProgressRecords(int $studentId): Collection
    {
        return StudentStageProgress::where('student_id', $studentId)->get();
    }

    /**
     * Get student progress for a specific stage
     * Read-only - returns null if not found
     */
    public function getStudentStageProgress(int $studentId, int $stageId): ?StudentStageProgress
    {
        return StudentStageProgress::where('student_id', $studentId)
            ->where('stage_id', $stageId)
            ->first();
    }

    /**
     * Get all student stage progress records by stage IDs
     * Read-only - returns collection indexed by stage_id
     */
    public function getStudentProgressByStageIds(int $studentId, array $stageIds): Collection
    {
        return StudentStageProgress::where('student_id', $studentId)
            ->whereIn('stage_id', $stageIds)
            ->get()
            ->keyBy('stage_id');
    }
}
