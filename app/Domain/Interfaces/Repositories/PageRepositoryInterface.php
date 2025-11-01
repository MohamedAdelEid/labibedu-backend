<?php

namespace App\Domain\Interfaces\Repositories;

use App\Infrastructure\Models\Page;
use Illuminate\Database\Eloquent\Collection;

interface PageRepositoryInterface
{
    public function find(int $id): ?Page;

    public function getLastPageOfBook(int $bookId): ?Page;

    public function getPageCount(int $bookId): int;

    public function getPagesByBookId(int $bookId): Collection;
}

