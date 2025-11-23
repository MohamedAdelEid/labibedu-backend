<?php

namespace App\Application\DTOs\Subject;

class GetSubjectsDTO
{
    public function __construct(
        public readonly ?int $gradeId = null,
        public readonly ?int $userId = null,
    ) {
    }

    public static function fromRequest(array $data): self
    {
        return new self(
            gradeId: $data['grade_id'] ?? null,
            userId: $data['user_id'] ?? null,
        );
    }
}

