<?php

namespace App\Application\Services;

use App\Application\DTOs\Library\ToggleFavoriteDTO;
use App\Application\DTOs\Library\OpenBookDTO;
use App\Domain\Interfaces\Repositories\BookRepositoryInterface;
use App\Domain\Interfaces\Repositories\StudentBookRepositoryInterface;
use App\Domain\Interfaces\Repositories\PageRepositoryInterface;
use App\Application\Exceptions\Book\BookNotFoundException;
use App\Application\Exceptions\Book\PageNotFoundException;
use App\Infrastructure\Models\StudentBook;

class StudentBookService
{
    public function __construct(
        private BookRepositoryInterface $bookRepository,
        private StudentBookRepositoryInterface $studentBookRepository,
        private PageRepositoryInterface $pageRepository,
    ) {
    }

    /**
     * Toggle favorite status for a book
     * Returns the StudentBook model
     */
    public function toggleFavorite(ToggleFavoriteDTO $dto): StudentBook
    {
        // Validate book exists
        $book = $this->bookRepository->findOrFail($dto->bookId);

        // Toggle favorite and return the model
        return $this->studentBookRepository->toggleFavorite($dto->studentId, $dto->bookId);
    }

    /**
     * Open a book and optionally update last read page
     * Returns the StudentBook model
     */
    public function openBook(OpenBookDTO $dto): StudentBook
    {
        // Validate book exists
        $book = $this->bookRepository->findOrFail($dto->bookId);

        // If page ID provided, validate it exists and belongs to the book
        if ($dto->pageId) {
            $page = $this->pageRepository->find($dto->pageId);

            if (!$page) {
                throw new PageNotFoundException('Page not found.');
            }

            if ($page->book_id !== $dto->bookId) {
                throw new PageNotFoundException('Page does not belong to this book.');
            }

            // Update last read page and return the model
            return $this->studentBookRepository->updateLastReadPage($dto->studentId, $dto->bookId, $dto->pageId);
        } else {
            // Just create/update the student_books record to mark it as unlocked
            return $this->studentBookRepository->createOrUpdate($dto->studentId, $dto->bookId, []);
        }
    }
}

