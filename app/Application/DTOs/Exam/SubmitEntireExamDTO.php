<?php

namespace App\Application\DTOs\Exam;

class SubmitEntireExamDTO
{
    public function __construct(
        public readonly int $studentId,
        public readonly int $examTrainingId,
    ) {}
}
