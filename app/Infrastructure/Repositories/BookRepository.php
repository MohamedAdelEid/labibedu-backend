<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Interfaces\Repositories\BookRepositoryInterface;
use App\Infrastructure\Models\Book;
use App\Infrastructure\Models\BookProgress;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BookRepository extends BaseRepository implements BookRepositoryInterface
{
    public function __construct(Book $model)
    {
        parent::__construct($model);
    }

    public function findOrFail(int $id, array $columns = ['*']): Book
    {
        return $this->model->findOrFail($id, $columns);
    }

    public function getProgress(int $studentId, int $bookId)
    {
        return BookProgress::where('student_id', $studentId)
            ->where('book_id', $bookId)
            ->first();
    }

    public function updateProgress(int $studentId, int $bookId, array $data)
    {
        return BookProgress::updateOrCreate(
            [
                'student_id' => $studentId,
                'book_id' => $bookId,
            ],
            $data
        );
    }

    public function getByRelatedTrainingId(int $trainingId): \Illuminate\Support\Collection
    {
        return $this->model->where('related_training_id', $trainingId)->get();
    }

    public function getAllLibraryBooks(
        int $studentId,
        ?int $levelId,
        ?string $search,
        int $perPage
    ): LengthAwarePaginator {
        $query = $this->model->query()
            ->with(['subject', 'level', 'relatedTraining'])
            ->where(function ($q) use ($studentId) {
                $q->where('is_in_library', true)
                    ->orWhereHas('studentBooks', function ($sq) use ($studentId) {
                        $sq->where('student_id', $studentId);
                    });
            });

        return $this->applyFiltersAndPaginate($query, $levelId, $search, $perPage);
    }

    public function getStudentBooks(
        int $studentId,
        ?int $levelId,
        ?string $search,
        int $perPage
    ): LengthAwarePaginator {
        $query = $this->model->query()
            ->with(['subject', 'level', 'relatedTraining'])
            ->whereHas('studentBooks', function ($q) use ($studentId) {
                $q->where('student_id', $studentId);
            });

        return $this->applyFiltersAndPaginate($query, $levelId, $search, $perPage);
    }

    public function getFavoriteBooks(
        int $studentId,
        ?int $levelId,
        ?string $search,
        int $perPage
    ): LengthAwarePaginator {
        $query = $this->model->query()
            ->with(['subject', 'level', 'relatedTraining'])
            ->whereHas('studentBooks', function ($q) use ($studentId) {
                $q->where('student_id', $studentId)
                    ->where('is_favorite', true);
            });

        return $this->applyFiltersAndPaginate($query, $levelId, $search, $perPage);
    }

    private function applyFiltersAndPaginate($query, ?int $levelId, ?string $search, int $perPage): LengthAwarePaginator
    {
        // Filter by level if provided
        if ($levelId) {
            $query->where('level_id', $levelId);
        }

        // Search by title if provided
        if ($search) {
            $query->where('title', 'LIKE', "%{$search}%");
        }

        return $query->paginate($perPage);
    }
}
