<?php

namespace App\Domain\Services;

use App\Domain\Factories\AnswerCheckerFactory;
use App\Domain\Enums\QuestionType;

class AnswerEvaluationService
{
    public function __construct(
        private AnswerCheckerFactory $answerCheckerFactory
    ) {
    }

    public function evaluateQuestion($question, $answer, $attempt): array
    {
        $isCorrect = $answer ? $this->answerCheckerFactory->check($question, $answer) : null;

        return [
            'question' => $question,
            'answer' => $answer,
            'is_correct' => $isCorrect,
            'correct_answer_data' => $this->getCorrectAnswerData($question, $answer),
            'answered_data' => $answer ? $this->getAnsweredData($question, $answer) : null,
            'show_correct_answers' => $attempt && $attempt->isFinished() || $question->examTraining->isTraining(),,
        ];
    }

    private function getCorrectAnswerData($question, $answer): mixed
    {
        return match ($question->type->value) {
            QuestionType::CHOICE->value => [
                'correct_option_id' => $question->options->where('is_correct', true)->first()?->id,
            ],
            QuestionType::TRUE_FALSE->value => [
                'correct' => $question->options->where('is_correct', true)->first()?->is_correct ?? false,
            ],
            QuestionType::CONNECT->value => [
                'correct_pairs' => $question->optionPairs
                    ->map(fn($pair) => [
                        'left_id' => $pair->left_option_id,
                        'right_id' => $pair->right_option_id,
                    ])
                    ->values()
                    ->toArray(),
            ],
            QuestionType::ARRANGE->value => [
                'correct_order' => $question->options
                    ->sortBy('arrange_order')
                    ->pluck('id')
                    ->toArray(),
            ],
            QuestionType::WRITTEN->value => [
                'is_correct' => $answer && $answer->grade
                    ? $answer->grade->is_correct
                    : false,
                'text' => $question->model_answer ?? '',
            ],
            default => null,
        };
    }

    private function getAnsweredData($question, $answer): mixed
    {
        return match ($question->type->value) {
            QuestionType::CHOICE->value => [
                'option_id' => $answer->option_id,
            ],
            QuestionType::TRUE_FALSE->value => [
                'is_correct' => $answer->true_false_answer ?? false,
            ],
            QuestionType::CONNECT->value => [
                'pairs' => $answer->pairs
                    ->map(fn($pair) => [
                        'left_id' => $pair->left_option_id,
                        'right_id' => $pair->right_option_id,
                    ])
                    ->values()
                    ->toArray(),
            ],
            QuestionType::ARRANGE->value => [
                'order' => $answer->orders
                    ->sortBy('order')
                    ->pluck('option_id')
                    ->toArray(),
            ],
            QuestionType::WRITTEN->value => [
                'text' => $answer->user_answer,
            ],
            default => null,
        };
    }
}
