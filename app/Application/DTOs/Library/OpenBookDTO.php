<?php

namespace App\Application\DTOs\Library;

class OpenBookDTO
{
    public function __construct(
        public readonly int $studentId,
        public readonly int $bookId,
        public readonly ?int $pageId = null,
    ) {
    }
}

