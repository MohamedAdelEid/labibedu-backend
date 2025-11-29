<?php

namespace App\Presentation\Http\Resources\Exam;

use App\Domain\Enums\QuestionType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamSummaryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $examTraining = $this->resource['examTraining'];
        $attempt = $this->resource['attempt'];
        $questionsWithAnswers = $this->resource['questions_with_answers'];

        return [
            'exam' => [
                'id' => $examTraining->id,
                'title' => $examTraining->title,
                'description' => $examTraining->description,
                'type' => $examTraining->type->value,
            ],
            'statistics' => [
                'total_questions' => $this->resource['total_questions'],
                'total_marks' => $this->resource['total_marks'],
                'earned_marks' => $this->resource['earned_marks'],
                'earned_xp' => $this->resource['earned_xp'],
                'earned_coins' => $this->resource['earned_coins'],
                'correct_answers' => $this->resource['correct_answers'],
                'wrong_answers' => $this->resource['wrong_answers'],
            ],
            'questions' => $questionsWithAnswers->map(function ($item) {
                $question = $item['question'];
                $answer = $item['answer'];
                $isCorrect = $item['is_correct'];
                $evaluation = $item['evaluation'];

                return [
                    'id' => $question->id,
                    'title' => $question->title,
                    'type' => $question->type->value,
                    'language' => $question->language->value,
                    'xp' => $question->xp,
                    'coins' => $question->coins,
                    'marks' => $question->marks,
                    'options' => $this->formatOptions($question),
                    'is_correct' => $isCorrect,
                    'answered_data' => $evaluation['answered_data'] ?? null,
                    'correct_answer_data' => $evaluation['correct_answer_data'] ?? null,
                ];
            })->values(),
            'completed_at' => $attempt->end_time?->toIso8601String(),
        ];
    }

    private function formatOptions($question): mixed
    {
        if ($question->type->value === QuestionType::CONNECT->value) {
            $left = $question->options->where('side', 'left')->map(fn($opt) => [
                'id' => $opt->id,
                'text' => $opt->text,
                'image' => $opt->image,
            ])->values();

            $right = $question->options->where('side', 'right')->map(fn($opt) => [
                'id' => $opt->id,
                'text' => $opt->text,
                'image' => $opt->image,
            ])->values();

            return [
                'left' => $left,
                'right' => $right,
            ];
        }

        if ($question->type->value === QuestionType::TRUE_FALSE->value) {
            return null;
        }

        return $question->options->map(fn($opt) => [
            'id' => $opt->id,
            'text' => $opt->text,
            'image' => $opt->image,
        ])->values();
    }
}

