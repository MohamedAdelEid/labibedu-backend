<?php

namespace App\Domain\Interfaces\Repositories;

use App\Infrastructure\Models\Page;

interface PageRepositoryInterface
{
    public function find(int $id): ?Page;

    public function getLastPageOfBook(int $bookId): ?Page;

    public function getPageCount(int $bookId): int;
}

