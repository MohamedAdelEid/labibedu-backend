<?php

namespace App\Application\Services;

use App\Domain\Enums\JourneyContentType;
use App\Domain\Enums\JourneyLevelStatus;
use App\Domain\Enums\JourneyStageStatus;
use App\Domain\Enums\JourneyStageType;
use App\Domain\Enums\StudentStageStatus;
use App\Domain\Interfaces\Repositories\AssignmentRepositoryInterface;
use App\Domain\Interfaces\Repositories\BookRepositoryInterface;
use App\Domain\Interfaces\Repositories\ExamAttemptRepositoryInterface;
use App\Domain\Interfaces\Repositories\JourneyRepositoryInterface;
use App\Infrastructure\Models\Assignment;
use App\Infrastructure\Models\ExamTraining;

class JourneyDataService
{
    private const UPCOMING_EXAM_HOURS = 24;

    public function __construct(
        private JourneyRepositoryInterface $journeyRepository,
        private AssignmentRepositoryInterface $assignmentRepository,
        private BookRepositoryInterface $bookRepository,
        private ExamAttemptRepositoryInterface $examAttemptRepository,
        private JourneyService $journeyService,
        private ExamService $examService,
        private BookProgressService $bookProgressService,
        private VideoService $videoService
    ) {
    }

    public function getJourneyData(int $studentId): array
    {
        $exam = $this->getUpcomingExam($studentId);
        $assignmentCount = $this->getNotStartedAssignmentCount($studentId);
        $bookCard = $this->getSanaaInSpaceBook();
        $levels = $this->getLevelsWithProgress($studentId);

        return [
            'exam' => $exam,
            'assignmentCount' => $assignmentCount,
            'bookCard' => $bookCard,
            'levels' => $levels,
        ];
    }

    private function getUpcomingExam(int $studentId): ?array
    {
        $now = now();
        $futureTime = $now->copy()->addHours(self::UPCOMING_EXAM_HOURS);

        $assignment = $this->assignmentRepository->getUpcomingExam($studentId, $now, $futureTime);

        if (!$assignment) {
            return [
                'hasUpcoming' => false,
                'dateTime' => null,
                'id' => null,
                'title' => null,
            ];
        }

        $examTraining = $assignment->assignable;

        $latestAttempt = $this->examAttemptRepository->findLatestAttempt($studentId, $examTraining->id);

        if ($latestAttempt && $latestAttempt->isFinished()) {
            return [
                'hasUpcoming' => false,
                'dateTime' => null,
                'id' => null,
                'title' => null,
            ];
        }

        return [
            'hasUpcoming' => true,
            'dateTime' => $assignment->end_date?->toIso8601String(),
            'id' => $examTraining->id,
            'title' => $examTraining->title_ar ?? $examTraining->title,
        ];
    }

    private function getNotStartedAssignmentCount(int $studentId): int
    {
        return $this->assignmentRepository->getNotStartedCount($studentId);
    }

    private function getSanaaInSpaceBook(): ?array
    {
        $book = $this->bookRepository->findByTitle('سناء في الفضاء');

        if (!$book) {
            return null;
        }

        $pagesCount = $book->pages()->count();
        $level = $book->level;

        return [
            'id' => $book->id,
            'coverImage' => $book->cover,
            'title' => $book->title,
            'level' => $level ? [
                'id' => $level->id,
                'name_ar' => $level->name_ar ?? '',
                'name_en' => $level->name_en ?? '',
            ] : null,
            'pagesCount' => $pagesCount,
            'isAvailable' => true,
        ];
    }

    private function getLevelsWithProgress(int $studentId): array
    {
        $levels = $this->journeyRepository->getAllLevelsWithStages();
        $allStageIds = $levels->flatMap(fn($level) => $level->stages->pluck('id'))->toArray();
        $progressMap = $this->journeyRepository->getStudentProgressByStageIds($studentId, $allStageIds);

        $currentStageProgress = $this->journeyRepository->getCurrentStage($studentId);

        if (!$currentStageProgress && !empty($levels) && !empty($levels[0]->stages)) {
            $firstStage = $levels[0]->stages->first();
            $currentStageProgress = $this->journeyRepository->initializeFirstStage($studentId, $firstStage->id);
            $progressMap->put($firstStage->id, $currentStageProgress);
        }

        $currentStageId = $currentStageProgress?->stage_id;
        $currentStageInfo = $this->getCurrentStageInfo($levels, $currentStageId);

        $result = [];
        $previousLevelCompleted = true;

        foreach ($levels as $level) {
            $completedStages = $this->countCompletedStages($level, $progressMap, $studentId);
            $totalStages = $level->stages->count();
            $progressPercentage = $totalStages > 0 ? round(($completedStages / $totalStages) * 100, 2) : 0;

            $status = $previousLevelCompleted
                ? JourneyLevelStatus::UNLOCKED
                : JourneyLevelStatus::LOCKED;
            $previousLevelCompleted = $progressPercentage >= 100;

            $stages = $this->buildStages($level, $studentId, $progressMap, $currentStageInfo);

            $result[] = [
                'id' => $level->id,
                'number' => $level->order,
                'name_ar' => $level->name_ar,
                'name_en' => $level->name_en,
                'progressPercentage' => $progressPercentage,
                'status' => $status->value,
                'stages' => $stages,
            ];
        }

        return $result;
    }

    private function getCurrentStageInfo($levels, ?int $currentStageId): ?array
    {
        if (!$currentStageId) {
            return null;
        }

        foreach ($levels as $level) {
            foreach ($level->stages as $stage) {
                if ($stage->id === $currentStageId) {
                    return [
                        'id' => $stage->id,
                        'level_order' => $level->order,
                        'stage_order' => $stage->order,
                    ];
                }
            }
        }

        return null;
    }

    private function countCompletedStages($level, $progressMap, int $studentId): int
    {
        $count = 0;
        foreach ($level->stages as $stage) {
            $progress = $progressMap->get($stage->id);
            $totalLessons = $stage->contents->count();
            $completedLessons = $this->countCompletedContents($stage, $studentId);

            if ($totalLessons > 0 && $completedLessons === $totalLessons) {
                $count++;
            } elseif ($progress && $progress->status === StudentStageStatus::COMPLETED) {
                $count++;
            }
        }
        return $count;
    }

    private function buildStages($level, int $studentId, $progressMap, ?array $currentStageInfo): array
    {
        $stages = [];

        foreach ($level->stages as $stage) {
            $progress = $progressMap->get($stage->id);
            $totalLessons = $stage->contents->count();
            $completedLessons = $this->countCompletedContents($stage, $studentId);

            $isCurrentStage = $currentStageInfo && $currentStageInfo['id'] === $stage->id;
            $isBeforeCurrent = $currentStageInfo && $this->isStageBeforeCurrent($level, $stage, $currentStageInfo);
            $isAfterCurrent = $currentStageInfo && !$isBeforeCurrent && !$isCurrentStage;

            $status = $this->determineStageStatus($progress, $completedLessons, $totalLessons, $isCurrentStage, $isBeforeCurrent, $isAfterCurrent);

            $starsEarned = $progress?->earned_stars;
            $data = $this->getStageData($stage, $studentId, $status);

            $stages[] = [
                'id' => $stage->id,
                'type' => JourneyStageType::fromDatabaseType($stage->type)->value,
                'status' => $status->value,
                'starsEarned' => $starsEarned,
                'completedLessons' => $status !== JourneyStageStatus::LOCKED ? $completedLessons : null,
                'totalLessons' => $status !== JourneyStageStatus::LOCKED ? $totalLessons : null,
                'data' => $data,
            ];
        }

        return $stages;
    }

    private function isStageBeforeCurrent($level, $stage, array $currentStageInfo): bool
    {
        $levelOrder = $level->order;
        $stageOrder = $stage->order;
        $currentLevelOrder = $currentStageInfo['level_order'];
        $currentStageOrder = $currentStageInfo['stage_order'];

        if ($levelOrder < $currentLevelOrder) {
            return true;
        }

        if ($levelOrder === $currentLevelOrder && $stageOrder < $currentStageOrder) {
            return true;
        }

        return false;
    }

    private function determineStageStatus($progress, int $completedLessons, int $totalLessons, bool $isCurrentStage, bool $isBeforeCurrent, bool $isAfterCurrent): JourneyStageStatus
    {
        if ($isCurrentStage) {
            if ($completedLessons === $totalLessons && $totalLessons > 0) {
                return JourneyStageStatus::COMPLETED;
            }
            return JourneyStageStatus::CURRENT;
        }

        if ($isBeforeCurrent) {
            if ($completedLessons === $totalLessons && $totalLessons > 0) {
                return JourneyStageStatus::COMPLETED;
            }
            if ($progress && $progress->status === StudentStageStatus::COMPLETED) {
                return JourneyStageStatus::COMPLETED;
            }
            return JourneyStageStatus::COMPLETED;
        }

        if ($isAfterCurrent) {
            return JourneyStageStatus::LOCKED;
        }

        return JourneyStageStatus::LOCKED;
    }

    private function countCompletedContents($stage, int $studentId): int
    {
        $count = 0;
        foreach ($stage->contents as $content) {
            $isCompleted = match ($content->content_type) {
                'book' => $this->isBookContentCompleted($studentId, $content->content_id),
                'video' => $this->videoService->isVideoCompleted($studentId, $content->content_id),
                'examTraining' => $this->examService->isExamTrainingCompleted($studentId, $content->content_id),
                default => false,
            };

            if ($isCompleted) {
                $count++;
            }
        }
        return $count;
    }

    private function isBookContentCompleted(int $studentId, int $bookId): bool
    {
        $book = $this->bookRepository->findOrFail($bookId);
        $isRead = $this->bookProgressService->isRead($book, $studentId);

        if (!$isRead) {
            return false;
        }

        if ($book->related_training_id) {
            return $this->examService->isExamTrainingCompleted($studentId, $book->related_training_id);
        }

        return true;
    }

    private function getStageData($stage, int $studentId, JourneyStageStatus $status): ?array
    {
        if ($status === JourneyStageStatus::LOCKED) {
            return null;
        }

        $currentContent = $this->getCurrentContent($stage, $studentId);

        if (!$currentContent) {
            return null;
        }

        return [
            'contentType' => $this->mapContentType($currentContent['type'], $currentContent['examTraining'] ?? null),
            'contentId' => (string) $currentContent['id'],
        ];
    }

    private function getCurrentContent($stage, int $studentId): ?array
    {
        foreach ($stage->contents as $content) {
            $isCompleted = match ($content->content_type) {
                'book' => $this->isBookContentCompleted($studentId, $content->content_id),
                'video' => $this->videoService->isVideoCompleted($studentId, $content->content_id),
                'examTraining' => $this->examService->isExamTrainingCompleted($studentId, $content->content_id),
                default => false,
            };

            if (!$isCompleted) {
                $examTraining = null;
                if ($content->content_type === 'examTraining') {
                    $examTraining = ExamTraining::find($content->content_id);
                }

                return [
                    'type' => $content->content_type,
                    'id' => $content->content_id,
                    'examTraining' => $examTraining,
                ];
            }
        }

        return null;
    }

    private function mapContentType(string $type, ?ExamTraining $examTraining = null): string
    {
        if ($type === 'examTraining' && $examTraining) {
            return JourneyContentType::fromDatabaseType($type, $examTraining->isExam())->value;
        }

        return JourneyContentType::fromDatabaseType($type)->value;
    }
}

