<?php

namespace App\Application\Services;

use App\Domain\Interfaces\Repositories\JourneyRepositoryInterface;
use Illuminate\Support\Collection;

class JourneyService
{
    public function __construct(
        private JourneyRepositoryInterface $journeyRepository
    ) {
    }

    /**
     * Get journey data for a student with progress and completion calculations
     */
    public function getStudentJourney(int $studentId): array
    {
        $levels = $this->journeyRepository->getAllLevelsWithStages();
        $studentProgress = $this->journeyRepository->getStudentProgress($studentId);

        // Create a lookup map for student progress by stage_id
        $progressMap = $studentProgress->keyBy('stage_id');

        $journeyData = [];
        $previousLevelCompleted = true;

        foreach ($levels as $level) {
            $stages = $level->stages;
            $totalStages = $stages->count();
            $completedStages = 0;

            $stagesData = [];
            $previousStageCompleted = true;

            foreach ($stages as $stage) {
                $stageId = $stage->id;
                $totalContent = $stage->contents->count();

                // Get or create progress for this stage
                $progress = $progressMap->get($stageId);
                if (!$progress) {
                    $progress = $this->journeyRepository->getOrCreateStudentStageProgress($studentId, $stageId);
                }

                // Count completed contents
                $completedContents = $this->journeyRepository->countCompletedContents($studentId, $stageId);

                // Determine stage status based on completed contents
                $stageStatus = $this->calculateStageStatus($completedContents, $totalContent, $progress->status);

                // Update progress status if all contents are completed
                if ($completedContents === $totalContent && $totalContent > 0) {
                    $stageStatus = 'completed';
                }

                // Apply sequential locking: stage is locked if previous stage isn't completed
                $isLocked = !$previousStageCompleted;

                // Count completed stages for level completion
                if ($stageStatus === 'completed') {
                    $completedStages++;
                    $previousStageCompleted = true;
                } else {
                    $previousStageCompleted = false;
                }

                $stagesData[] = [
                    'id' => $stage->id,
                    'type' => $stage->type,
                    'total_content' => $totalContent,
                    'is_locked' => $isLocked,
                    'student_stage_data' => [
                        'earned_stars' => $progress->earned_stars ?? 0,
                        'status' => $stageStatus,
                        'completed_contents' => $completedContents,
                    ],
                ];
            }

            // Calculate level completion percentage
            $completionPercentage = $totalStages > 0
                ? round(($completedStages / $totalStages) * 100, 2)
                : 0;

            // Determine level status
            $levelStatus = $this->calculateLevelStatus(
                $previousLevelCompleted,
                $completionPercentage
            );

            // Update previous level completion status for next level
            $previousLevelCompleted = $completionPercentage === 100.0;

            $journeyData[] = [
                'id' => $level->id,
                'name' => $level->name,
                'completion_percentage' => $completionPercentage,
                'status' => $levelStatus,
                'stages' => $stagesData,
            ];
        }

        return $journeyData;
    }

    /**
     * Calculate stage status based on completed contents and current status
     */
    private function calculateStageStatus(int $completedContents, int $totalContent, string $currentStatus): string
    {
        if ($completedContents === 0) {
            return 'not_started';
        }

        if ($completedContents === $totalContent && $totalContent > 0) {
            return 'completed';
        }

        return 'in_progress';
    }

    /**
     * Calculate level status based on previous level completion and current progress
     */
    private function calculateLevelStatus(bool $previousLevelCompleted, float $completionPercentage): string
    {
        if (!$previousLevelCompleted) {
            return 'locked';
        }

        return 'unlocked';
    }

    /**
     * Check if a specific content is completed for a student
     */
    public function isContentCompleted(int $studentId, string $contentType, int $contentId): bool
    {
        return match ($contentType) {
            'book' => $this->journeyRepository->isBookCompleted($studentId, $contentId),
            'video' => $this->journeyRepository->isVideoCompleted($studentId, $contentId),
            'examTraining' => $this->journeyRepository->isExamTrainingCompleted($studentId, $contentId),
            default => false,
        };
    }
}

