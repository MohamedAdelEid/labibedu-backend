<?php

namespace App\Application\Services;

use App\Domain\Interfaces\Services\BookServiceInterface;
use App\Domain\Interfaces\Repositories\StudentBookRepositoryInterface;
use App\Domain\Interfaces\Repositories\BookRepositoryInterface;
use Exception;

class BookService implements BookServiceInterface
{
    public function __construct(
        private StudentBookRepositoryInterface $studentBookRepository,
        private BookRepositoryInterface $bookRepository,
        private BookProgressService $bookProgressService
    ) {
    }

    /**
     * Check if book is completed and get XP/coins values
     */
    public function getBookCompletionScoring(int $studentId, int $bookId): array
    {
        // Get the book
        $book = $this->bookRepository->findOrFail($bookId);

        if (!$book) {
            throw new Exception('Book not found');
        }

        // Check if book is completed using BookProgressService
        $isCompleted = $this->bookProgressService->getReadingStatus($book, $studentId) === 'completed';

        // if (!$isCompleted) {
        //     throw new Exception('Book must be completed first before scoring');
        // }

        // Get student book record for updated_at timestamp
        $studentBook = $this->studentBookRepository->findByStudentAndBook($studentId, $bookId);

        return [
            'success' => true,
            'xp' => $book->xp ?? 0,
            'coins' => $book->coins ?? 0,
            'book_id' => $bookId,
            'completed_at' => $studentBook?->updated_at ?? now(),
        ];
    }

    /**
     * Get count of books read by student
     */
    public function getBooksReadCount(int $studentId): int
    {
        // Get all books that the student has opened
        $studentBookIds = $this->studentBookRepository->getStudentBookIds($studentId);

        if ($studentBookIds->isEmpty()) {
            return 0;
        }

        // Count how many of these books are fully read
        $readCount = 0;

        foreach ($studentBookIds as $bookId) {
            $book = $this->bookRepository->findOrFail($bookId);

            if ($book && $this->bookProgressService->isRead($book, $studentId)) {
                $readCount++;
            }
        }

        return $readCount;
    }

    /**
     * Check if a book is completed by the student
     * A book is completed when student has read the last page AND completed related training (if exists)
     * 
     * @param int $studentId
     * @param int $bookId
     * @return bool
     */
    public function isBookCompleted(int $studentId, int $bookId): bool
    {
        try {
            $book = $this->bookRepository->findOrFail($bookId);
            $readingStatus = $this->bookProgressService->getReadingStatus($book, $studentId);
            return $readingStatus === 'completed';
        } catch (\Exception $e) {
            return false;
        }
    }
}
