<?php

namespace App\Application\Services;

use App\Domain\Interfaces\Repositories\JourneyRepositoryInterface;
use App\Domain\Enums\StudentStageStatus;
use App\Domain\Interfaces\Services\BookServiceInterface;
use App\Domain\Interfaces\Services\VideoServiceInterface;
use Illuminate\Support\Collection;

class JourneyService
{
    public function __construct(
        private JourneyRepositoryInterface $journeyRepository,
        private BookServiceInterface $bookService,
        private VideoServiceInterface $videoService,
        private ExamService $examService
    ) {
    }

    /**
     * Get journey levels with stages for a student
     * Returns Eloquent models/collections for Resources to format
     */
    public function getLevelsForStudent(int $studentId): Collection
    {
        $levels = $this->journeyRepository->getAllLevelsWithStages();

        // Get all stage IDs
        $allStageIds = $levels->flatMap(function ($level) {
            return $level->stages->pluck('id');
        })->toArray();

        // Get all student progress records at once
        $progressMap = $this->journeyRepository->getStudentProgressByStageIds($studentId, $allStageIds);

        // Attach calculated data to each level
        $previousLevelCompleted = true;

        foreach ($levels as $level) {
            // Calculate level completion
            $levelData = $this->calculateLevelCompletion($level, $studentId, $progressMap);

            // Determine level status
            $level->calculated_status = $this->calculateLevelStatus($previousLevelCompleted);
            $level->completion_percentage = $levelData['completion_percentage'];
            $level->completed_stages_count = $levelData['completed_stages_count'];
            $level->total_stages_count = $levelData['total_stages_count'];

            // Update for next level
            $previousLevelCompleted = $levelData['completion_percentage'] >= 100;

            // Process stages
            $this->processStagesForLevel($level, $studentId, $progressMap);
        }

        return $levels;
    }

    /**
     * Calculate level completion percentage
     * Returns array with completion data
     */
    public function calculateLevelCompletion($level, int $studentId, Collection $progressMap): array
    {
        $totalStages = $level->stages->count();
        $completedStages = $this->countCompletedStages($level, $studentId, $progressMap);

        $completionPercentage = $totalStages > 0
            ? round(($completedStages / $totalStages) * 100, 2)
            : 0;

        return [
            'completion_percentage' => $completionPercentage,
            'completed_stages_count' => $completedStages,
            'total_stages_count' => $totalStages,
        ];
    }

    /**
     * Count completed stages in a level
     * Uses student_stage_progress table
     */
    public function countCompletedStages($level, int $studentId, Collection $progressMap): int
    {
        $completedCount = 0;

        foreach ($level->stages as $stage) {
            $progress = $progressMap->get($stage->id);

            if ($progress && $progress->status === StudentStageStatus::COMPLETED->value) {
                $completedCount++;
            }
        }

        return $completedCount;
    }

    /**
     * Calculate level status (locked/unlocked)
     */
    private function calculateLevelStatus(bool $previousLevelCompleted): string
    {
        return $previousLevelCompleted ? 'unlocked' : 'locked';
    }

    /**
     * Process stages for a level and attach calculated data
     */
    private function processStagesForLevel($level, int $studentId, Collection $progressMap): void
    {
        $previousStageCompleted = true;

        foreach ($level->stages as $stage) {
            $stageStatusData = $this->calculateStageStatus($stage, $studentId, $progressMap, $previousStageCompleted);

            // Attach calculated data to stage model
            $stage->calculated_status = $stageStatusData['status'];
            $stage->is_open = $stageStatusData['is_open'];
            $stage->earned_stars = $stageStatusData['earned_stars'];
            $stage->completed_contents = $stageStatusData['completed_contents'];
            $stage->total_content = $stageStatusData['total_content'];

            // Update for next stage
            $previousStageCompleted = $stageStatusData['status'] === StudentStageStatus::COMPLETED->value;
        }
    }

    /**
     * Calculate stage status for a student
     * Read-only - determines status, is_open, earned_stars, completed_contents
     * No side effects, no DB writes
     */
    public function calculateStageStatus($stage, int $studentId, Collection $progressMap, bool $previousStageCompleted): array
    {
        // Get existing progress record (read-only)
        $progress = $progressMap->get($stage->id);

        $totalContent = $stage->contents->count();
        $completedContents = $this->countCompletedContents($stage, $studentId);

        // Determine status
        $status = $this->determineStageStatus($progress, $completedContents, $totalContent);

        // Stage is open if previous stage is completed (or this is the first stage)
        $isOpen = $previousStageCompleted;

        return [
            'status' => $status,
            'is_open' => $isOpen,
            'earned_stars' => $progress->earned_stars ?? 0,
            'completed_contents' => $completedContents,
            'total_content' => $totalContent,
        ];
    }

    /**
     * Determine stage status based on progress record and completion
     */
    private function determineStageStatus($progress, int $completedContents, int $totalContent): string
    {
        // If no progress record and no completed contents, it's not started
        if (!$progress && $completedContents === 0) {
            return StudentStageStatus::NOT_STARTED->value;
        }

        // If all contents are completed
        if ($completedContents === $totalContent && $totalContent > 0) {
            return StudentStageStatus::COMPLETED->value;
        }

        // If there's a progress record with completed status
        if ($progress && $progress->status === StudentStageStatus::COMPLETED->value) {
            return StudentStageStatus::COMPLETED->value;
        }

        // If some contents are completed or progress exists
        if ($completedContents > 0 || $progress) {
            return StudentStageStatus::IN_PROGRESS->value;
        }

        return StudentStageStatus::NOT_STARTED->value;
    }

    /**
     * Count completed contents in a stage
     * Delegates to appropriate service per content type
     */
    public function countCompletedContents($stage, int $studentId): int
    {
        $completedCount = 0;

        foreach ($stage->contents as $content) {
            $isCompleted = match ($content->content_type) {
                'book' => $this->bookService->isBookCompleted($studentId, $content->content_id),
                'video' => $this->videoService->isVideoCompleted($studentId, $content->content_id),
                'examTraining' => $this->examService->isExamTrainingCompleted($studentId, $content->content_id),
                default => false,
            };

            if ($isCompleted) {
                $completedCount++;
            }
        }

        return $completedCount;
    }

    /**
     * Check if a stage is open for a student
     * A stage is open if the previous stage in the same level is completed
     * First stage of a level is open if level is unlocked
     */
    public function isStageOpen($stage, int $studentId, bool $previousStageCompleted): bool
    {
        return $previousStageCompleted;
    }
}
