<?php

namespace App\Application\DTOs\Library;

class ToggleFavoriteDTO
{
    public function __construct(
        public readonly int $studentId,
        public readonly int $bookId,
    ) {
    }
}

