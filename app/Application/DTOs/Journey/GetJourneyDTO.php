<?php

namespace App\Application\DTOs\Journey;

class GetJourneyDTO
{
    public function __construct(
        public readonly int $studentId
    ) {
    }

    public static function fromRequest(int $studentId): self
    {
        return new self($studentId);
    }
}

