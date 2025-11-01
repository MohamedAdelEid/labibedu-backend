<?php

namespace App\Presentation\Http\Controllers\Api;

use App\Application\DTOs\Library\GetLibraryBooksDTO;
use App\Application\DTOs\Library\OpenBookDTO;
use App\Application\DTOs\Library\ToggleFavoriteDTO;
use App\Application\DTOs\Library\GetBookPagesDTO;
use App\Infrastructure\Facades\LibraryFacade;
use App\Infrastructure\Helpers\ApiResponse;
use App\Presentation\Http\Requests\Library\GetLibraryBooksRequest;
use App\Presentation\Http\Requests\Library\OpenBookRequest;
use App\Presentation\Http\Resources\Library\BookResource;
use App\Presentation\Http\Resources\Library\StudentBookResource;
use App\Presentation\Http\Resources\Library\BookPagesResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class LibraryController extends Controller
{
    public function __construct(
        private LibraryFacade $libraryFacade
    ) {
    }

    /**
     * Get student's library books with filters and pagination
     */
    public function index(GetLibraryBooksRequest $request): JsonResponse
    {
        $studentId = auth()->user()->student->id;

        $dto = GetLibraryBooksDTO::fromRequest($request->validated(), $studentId);

        // Get paginated books from facade
        $books = $this->libraryFacade->getLibraryBooks($dto);

        // Get favorite count
        $favoriteCount = $this->libraryFacade->getFavoriteCount($studentId);

        // Transform books using resource
        $booksCollection = BookResource::collection($books);

        // Return paginated response with additional metadata
        return ApiResponse::paginatedWithData(
            $books,
            ['favouriteCount' => $favoriteCount],
            $booksCollection
        );
    }

    /**
     * Toggle favorite status for a book
     */
    public function toggleFavorite(int $bookId): JsonResponse
    {
        $studentId = auth()->user()->student->id;

        $dto = new ToggleFavoriteDTO($studentId, $bookId);

        $studentBook = $this->libraryFacade->toggleFavorite($dto);

        $message = $studentBook->is_favorite
            ? 'Book added to favorites.'
            : 'Book removed from favorites.';

        return ApiResponse::success(
            new StudentBookResource($studentBook),
            $message
        );
    }

    /**
     * Open a book and optionally update last read page
     */
    public function openBook(int $bookId, OpenBookRequest $request): JsonResponse
    {
        $studentId = auth()->user()->student->id;
        $pageId = $request->input('page_id');

        $dto = new OpenBookDTO($studentId, $bookId, $pageId);

        $studentBook = $this->libraryFacade->openBook($dto);

        return ApiResponse::success(
            new StudentBookResource($studentBook),
            'Book opened successfully.'
        );
    }

    /**
     * Get all pages for a specific book with student's last read page
     */
    public function getBookPages(int $bookId): JsonResponse
    {
        $studentId = auth()->user()->student->id;

        $dto = new GetBookPagesDTO($studentId, $bookId);

        $bookPages = $this->libraryFacade->getBookPages($dto);

        return ApiResponse::success(
            new BookPagesResource($bookPages)
        );
    }
}

