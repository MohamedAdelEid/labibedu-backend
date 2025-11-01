<?php

namespace App\Application\Services;

use App\Application\DTOs\Library\GetBookPagesDTO;
use App\Domain\Interfaces\Repositories\BookRepositoryInterface;
use App\Domain\Interfaces\Repositories\PageRepositoryInterface;
use App\Domain\Interfaces\Repositories\StudentBookRepositoryInterface;

class PageService
{
    public function __construct(
        private BookRepositoryInterface $bookRepository,
        private PageRepositoryInterface $pageRepository,
        private StudentBookRepositoryInterface $studentBookRepository,
    ) {
    }

    /**
     * Get all pages for a book along with the student's last read page
     * Returns an array with book_id, last_read_page_id, and pages collection
     */
    public function getBookPages(GetBookPagesDTO $dto): array
    {
        // Validate book exists
        $book = $this->bookRepository->findOrFail($dto->bookId);

        // Get all pages for the book
        $pages = $this->pageRepository->getPagesByBookId($dto->bookId);

        // Get student's book record to find last read page
        $studentBook = $this->studentBookRepository->findByStudentAndBook($dto->studentId, $dto->bookId);

        return [
            'book_id' => $dto->bookId,
            'last_read_page_id' => $studentBook?->last_read_page_id,
            'pages' => $pages,
        ];
    }
}

