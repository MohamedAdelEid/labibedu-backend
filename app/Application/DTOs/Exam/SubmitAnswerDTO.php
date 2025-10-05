<?php

namespace App\Application\DTOs\Exam;

class SubmitAnswerDTO
{
    public function __construct(
        public readonly int $studentId,
        public readonly int $questionId,
        public readonly array $selections,
        public readonly ?string $userAnswer = null,
    ) {}

    public static function fromRequest(array $data, int $studentId): self
    {
        return new self(
            studentId: $studentId,
            questionId: $data['question_id'],
            selections: $data['selections'] ?? [],
            userAnswer: $data['user_answer'] ?? null,
        );
    }
}