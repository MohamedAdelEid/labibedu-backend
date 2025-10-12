<?php

namespace App\Application\DTOs\Exam;

class SubmitAnswerDTO
{
    public function __construct(
        public readonly int $studentId,
        public readonly int $questionId,
        public readonly ?array $selectedOptionIds,
        public readonly ?array $connectPairs,
        public readonly ?array $arrangeOptionIds,
        public readonly ?string $writtenAnswer,
        public readonly int $timeSpent = 0,
    ) {}

    public function toAnswerArray(): array
    {
        return array_filter([
            'selected_option_ids' => $this->selectedOptionIds,
            'connect_pairs' => $this->connectPairs,
            'arrange_option_ids' => $this->arrangeOptionIds,
            'written_answer' => $this->writtenAnswer,
        ], fn($value) => $value !== null);
    }
}
