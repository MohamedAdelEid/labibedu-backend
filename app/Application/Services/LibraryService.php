<?php

namespace App\Application\Services;

use App\Application\DTOs\Library\GetLibraryBooksDTO;
use App\Domain\Enums\LibraryScope;
use App\Domain\Interfaces\Repositories\BookRepositoryInterface;
use App\Domain\Interfaces\Repositories\StudentBookRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class LibraryService
{
    public function __construct(
        private BookRepositoryInterface $bookRepository,
        private StudentBookRepositoryInterface $studentBookRepository,
        private BookProgressService $bookProgressService,
    ) {
    }

    /**
     * Get books for student library with computed fields
     * Returns paginated collection with enriched book models
     */
    public function getBooksForStudent(GetLibraryBooksDTO $dto): LengthAwarePaginator
    {
        // Call the appropriate repository method based on scope using enum
        $books = match ($dto->scope) {
            LibraryScope::MINE => $this->bookRepository->getStudentBooks(
                $dto->studentId,
                $dto->levelId,
                $dto->search,
                $dto->perPage
            ),
            LibraryScope::FAVORITE => $this->bookRepository->getFavoriteBooks(
                $dto->studentId,
                $dto->levelId,
                $dto->search,
                $dto->perPage
            ),
            LibraryScope::ALL => $this->bookRepository->getAllLibraryBooks(
                $dto->studentId,
                $dto->levelId,
                $dto->search,
                $dto->perPage
            ),
        };

        // Enrich each book with computed attributes
        return $books->through(function ($book) use ($dto) {
            return $this->enrichBookWithComputedFields($book, $dto->studentId);
        });
    }

    /**
     * Get favorite count for a student
     */
    public function getFavoriteCount(int $studentId): int
    {
        return $this->studentBookRepository->getFavoriteCount($studentId);
    }

    /**
     * Enrich a book with computed attributes
     */
    private function enrichBookWithComputedFields($book, int $studentId): object
    {
        $studentBook = $this->studentBookRepository->findByStudentAndBook($studentId, $book->id);

        // Add computed fields as properties
        $book->is_new = $this->bookProgressService->isNew($studentId, $book->id);
        $book->is_locked = $this->bookProgressService->isLocked($studentId, $book->id);
        $book->has_training = !is_null($book->related_training_id);
        $book->is_favourite = $studentBook ? $studentBook->is_favorite : false;
        $book->reading_status = $this->bookProgressService->getReadingStatus($book, $studentId);
        $book->is_read = $this->bookProgressService->isRead($book, $studentId);

        return $book;
    }
}


