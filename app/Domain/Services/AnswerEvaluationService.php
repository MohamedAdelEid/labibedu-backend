<?php

namespace App\Domain\Services;

use App\Domain\Factories\AnswerCheckerFactory;
use App\Domain\Enums\QuestionType;

class AnswerEvaluationService
{
    public function __construct(
        private AnswerCheckerFactory $answerCheckerFactory
    ) {}

    /**
     * Evaluate a question and prepare all answer data
     * 
     * @param mixed $question
     * @param mixed $answer
     * @param mixed $attempt
     * @return array
     */
    public function evaluateQuestion($question, $answer, $attempt): array
    {
        $isCorrect = $answer ? $this->answerCheckerFactory->check($question, $answer) : null;

        return [
            'question' => $question,
            'answer' => $answer,
            'is_correct' => $isCorrect,
            'correct_answer_data' => $this->getCorrectAnswerData($question, $answer),
            'answered_data' => $answer ? $this->getAnsweredData($question, $answer) : null,
            'show_correct_answers' => $attempt && $attempt->isFinished() || $question->examTraining->isTraining(),
        ];
    }
    
    /**
     * Get the correct answer data for a question
     */
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
                'correct_pairs' => $question->options
                    ->where('side', 'left')
                    ->map(fn($opt) => [
                        'left_id' => $opt->id,
                        'right_id' => $opt->match_id,
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
                    ? $answer->grade->gained_marks > 0 
                    : false,
                'text' => $question->model_answer ?? '',
            ],
            default => null,
        };
    }

    /**
     * Get the student's answered data for a question
     */
    private function getAnsweredData($question, $answer): mixed
    {
        return match ($question->type->value) {
            QuestionType::CHOICE->value => [
                'option_id' => $answer->selections->first()?->option_id,
            ],
            QuestionType::TRUE_FALSE->value => [
                'is_correct' => $answer->selections->first()?->option->is_correct ?? false,
            ],
            QuestionType::CONNECT->value => [
                'pairs' => $answer->selections
                    ->sortBy('order')
                    ->chunk(2)
                    ->map(fn($pair) => [
                        'left_id' => $pair->first()->option_id,
                        'right_id' => $pair->last()->option_id,
                    ])
                    ->values()
                    ->toArray(),
            ],
            QuestionType::ARRANGE->value => [
                'order' => $answer->selections
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
