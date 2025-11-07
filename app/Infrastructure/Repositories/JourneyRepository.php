<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Interfaces\Repositories\JourneyRepositoryInterface;
use App\Infrastructure\Models\JourneyLevel;
use App\Infrastructure\Models\StudentStageProgress;
use App\Infrastructure\Models\StudentBook;
use App\Infrastructure\Models\VideoProgress;
use App\Infrastructure\Models\ExamAttempt;
use App\Infrastructure\Models\StageContent;
use Illuminate\Database\Eloquent\Collection;

class JourneyRepository implements JourneyRepositoryInterface
{
    /**
     * Get all journey levels with their stages ordered by order
     */
    public function getAllLevelsWithStages(): Collection
    {
        return JourneyLevel::with(['stages.contents'])
            ->orderBy('order')
            ->get();
    }

    /**
     * Get student's progress for all stages
     */
    public function getStudentProgress(int $studentId): Collection
    {
        return StudentStageProgress::where('student_id', $studentId)->get();
    }

    /**
     * Get or create student progress for a specific stage
     */
    public function getOrCreateStudentStageProgress(int $studentId, int $stageId): StudentStageProgress
    {
        return StudentStageProgress::firstOrCreate(
            [
                'student_id' => $studentId,
                'stage_id' => $stageId,
            ],
            [
                'earned_stars' => 0,
                'status' => 'not_started',
            ]
        );
    }

    /**
     * Check if a book is completed by student (book + related training)
     */
    public function isBookCompleted(int $studentId, int $bookId): bool
    {
        $studentBook = StudentBook::where('student_id', $studentId)
            ->where('book_id', $bookId)
            ->first();

        if (!$studentBook) {
            return false;
        }

        // Get the book to check if it has a related training
        $book = $studentBook->book;
        if (!$book || !$book->related_training_id) {
            // If no related training, just check if book exists in student_books
            return true;
        }

        // Check if related training is completed
        return $this->isExamTrainingCompleted($studentId, $book->related_training_id);
    }

    /**
     * Check if a video is completed by student
     */
    public function isVideoCompleted(int $studentId, int $videoId): bool
    {
        $progress = VideoProgress::where('student_id', $studentId)
            ->where('video_id', $videoId)
            ->where('is_completed', true)
            ->first();

        return $progress !== null;
    }

    /**
     * Check if an exam/training is completed by student
     */
    public function isExamTrainingCompleted(int $studentId, int $examTrainingId): bool
    {
        $attempt = ExamAttempt::where('student_id', $studentId)
            ->where('exam_training_id', $examTrainingId)
            ->where('status', 'finished')
            ->first();

        return $attempt !== null;
    }

    /**
     * Count completed contents for a student in a stage
     */
    public function countCompletedContents(int $studentId, int $stageId): int
    {
        $contents = StageContent::where('stage_id', $stageId)->get();
        $completedCount = 0;

        foreach ($contents as $content) {
            $isCompleted = match ($content->content_type) {
                'book' => $this->isBookCompleted($studentId, $content->content_id),
                'video' => $this->isVideoCompleted($studentId, $content->content_id),
                'examTraining' => $this->isExamTrainingCompleted($studentId, $content->content_id),
                default => false,
            };

            if ($isCompleted) {
                $completedCount++;
            }
        }

        return $completedCount;
    }
}

