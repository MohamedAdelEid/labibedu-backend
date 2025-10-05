<?php

namespace App\Application\DTOs\Exam;

class SubmitEntireExamDTO
{
    public function __construct(
        public readonly int $studentId,
        public readonly int $examTrainingId,
        public readonly array $answers,
    ) {}

    public static function fromRequest(array $data, int $studentId): self
    {
        return new self(
            studentId: $studentId,
            examTrainingId: $data['exam_training_id'],
            answers: $data['answers'],
        );
    }
}