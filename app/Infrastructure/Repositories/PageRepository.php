<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Interfaces\Repositories\PageRepositoryInterface;
use App\Infrastructure\Models\Page;

class PageRepository implements PageRepositoryInterface
{
    public function __construct(
        private Page $model
    ) {
    }

    public function find(int $id): ?Page
    {
        return $this->model->find($id);
    }

    public function getLastPageOfBook(int $bookId): ?Page
    {
        return $this->model
            ->where('book_id', $bookId)
            ->orderBy('id', 'desc')
            ->first();
    }

    public function getPageCount(int $bookId): int
    {
        return $this->model
            ->where('book_id', $bookId)
            ->count();
    }
}

