<?php

namespace App\Application\DTOs\Exam;

class SubmitEntireExamDTO
{
    public function __construct(
        public readonly int $studentId,
        public readonly int $examTrainingId,
        public readonly ?string $source = null,
        public readonly ?int $sourceId = null,
    ) {}
}
