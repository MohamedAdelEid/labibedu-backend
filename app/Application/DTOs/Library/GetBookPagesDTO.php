<?php

namespace App\Application\DTOs\Library;

class GetBookPagesDTO
{
    public function __construct(
        public readonly int $studentId,
        public readonly int $bookId,
    ) {
    }
}

