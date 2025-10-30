<?php

namespace App\Application\Services;

use App\Domain\Interfaces\Repositories\PageRepositoryInterface;
use App\Domain\Interfaces\Repositories\StudentBookRepositoryInterface;
use App\Domain\Interfaces\Repositories\ExamAttemptRepositoryInterface;
use App\Infrastructure\Models\Book;

class BookProgressService
{
    public function __construct(
        private StudentBookRepositoryInterface $studentBookRepository,
        private PageRepositoryInterface $pageRepository,
        private ExamAttemptRepositoryInterface $examAttemptRepository,
    ) {
    }

    /**
     * Calculate reading status for a book
     * 
     * @return string "not_started"|"in_progress"|"completed"
     */
    public function getReadingStatus(Book $book, int $studentId): string
    {
        $studentBook = $this->studentBookRepository->findByStudentAndBook($studentId, $book->id);

        // Not started if no student_books record OR last_read_page_id is null
        if (!$studentBook || !$studentBook->last_read_page_id) {
            return 'not_started';
        }

        // Check if student has finished the last page
        $lastPage = $this->pageRepository->getLastPageOfBook($book->id);

        if (!$lastPage) {
            return 'not_started';
        }

        $hasFinishedLastPage = $studentBook->last_read_page_id === $lastPage->id;

        // If book has related training, check if it's completed
        if ($book->related_training_id) {
            $hasCompletedTraining = $this->hasCompletedTraining($studentId, $book->related_training_id);

            // Only completed if both last page and training are done
            if ($hasFinishedLastPage && $hasCompletedTraining) {
                return 'completed';
            }

            return 'in_progress';
        }

        // No training, just check if last page is reached
        return $hasFinishedLastPage ? 'completed' : 'in_progress';
    }

    /**
     * Check if student has completed the training
     */
    private function hasCompletedTraining(int $studentId, int $trainingId): bool
    {
        $attempt = $this->examAttemptRepository->findLatestAttempt($studentId, $trainingId);

        return $attempt && $attempt->isFinished();
    }

    /**
     * Check if book is new for student
     */
    public function isNew(int $studentId, int $bookId): bool
    {
        $studentBook = $this->studentBookRepository->findByStudentAndBook($studentId, $bookId);

        // New if no record or no page has been read
        return !$studentBook || !$studentBook->last_read_page_id;
    }

    /**
     * Check if book is locked for student
     */
    public function isLocked(int $studentId, int $bookId): bool
    {
        $studentBook = $this->studentBookRepository->findByStudentAndBook($studentId, $bookId);

        // Locked if no student_books record exists
        return !$studentBook;
    }
}

