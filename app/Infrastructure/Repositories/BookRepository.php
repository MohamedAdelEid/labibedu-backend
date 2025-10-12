<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Interfaces\Repositories\BookRepositoryInterface;
use App\Infrastructure\Models\Book;
use App\Infrastructure\Models\BookProgress;

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
}
