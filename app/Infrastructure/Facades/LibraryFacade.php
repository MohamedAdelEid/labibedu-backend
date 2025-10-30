<?php

namespace App\Infrastructure\Facades;

use App\Application\DTOs\Library\GetLibraryBooksDTO;
use App\Application\DTOs\Library\ToggleFavoriteDTO;
use App\Application\DTOs\Library\OpenBookDTO;
use App\Application\Services\LibraryService;
use App\Application\Services\StudentBookService;
use App\Infrastructure\Models\StudentBook;

/**
 * LibraryFacade - Provides a simplified interface between Controller and Services
 * 
 * This facade acts as the entry point for all library-related operations,
 * coordinating between multiple services while keeping the controller thin.
 */
class LibraryFacade
{
    public function __construct(
        private LibraryService $libraryService,
        private StudentBookService $studentBookService,
    ) {
    }

    /**
     * Get library books for student with filters
     * Returns paginated collection with enriched books
     */
    public function getLibraryBooks(GetLibraryBooksDTO $dto)
    {
        return $this->libraryService->getBooksForStudent($dto);
    }

    /**
     * Get favorite count for student
     */
    public function getFavoriteCount(int $studentId): int
    {
        return $this->libraryService->getFavoriteCount($studentId);
    }

    /**
     * Toggle favorite status for a book
     * Returns StudentBook model
     */
    public function toggleFavorite(ToggleFavoriteDTO $dto): StudentBook
    {
        return $this->studentBookService->toggleFavorite($dto);
    }

    /**
     * Open a book and optionally update last read page
     * Returns StudentBook model
     */
    public function openBook(OpenBookDTO $dto): StudentBook
    {
        return $this->studentBookService->openBook($dto);
    }
}

