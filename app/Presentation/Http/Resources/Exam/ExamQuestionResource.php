<?php

namespace App\Presentation\Http\Resources\Exam;

use App\Domain\Enums\QuestionType;
use App\Infrastructure\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamQuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $question = $this->resource['question'];
        $answer = $this->resource['answer'];
        $answeredData = $this->resource['answered_data'];
        $correctAnswerData = $this->resource['correct_answer_data'];
        $showCorrectAnswers = $this->resource['show_correct_answers'];

        $data = [
            'id' => $question->id,
            'title' => $question->title,
            'type' => $question->type->value,
            'language' => $question->language->value,
            'xp' => $question->xp,
            'coins' => $question->coins,
            'marks' => $question->marks,
            'has_images' => $this->hasImages($question),
            'options' => $this->formatOptions($question),
            'answered' => $answeredData,
        ];

        if ($showCorrectAnswers) {
            $data['answers'] = $correctAnswerData;
        }

        return $data;
    }

    private function formatOptions($question): mixed
    {
        if ($question->type->value === QuestionType::CONNECT->value) {
            $left = $question->options->where('side', 'left')->map(fn($opt) => [
                'id' => $opt->id,
                'text' => $opt->text,
                'image' => $opt->image,
            ])->shuffle()->values();

            $right = $question->options->where('side', 'right')->map(fn($opt) => [
                'id' => $opt->id,
                'text' => $opt->text,
                'image' => $opt->image,
            ])->shuffle()->values();

            return [
                'left' => $left,
                'right' => $right,
            ];
        }

        if ($question->type->value === QuestionType::ARRANGE->value) {
            return $question->options->map(fn($opt) => [
                'id' => $opt->id,
                'text' => $opt->text,
                'image' => $opt->image,
            ])->shuffle()->values();
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

    private function hasImages($question): bool
    {
        if ($question->type->value === QuestionType::CHOICE->value) {
            return $question->options->contains(fn($opt) => !empty($opt->image));
        }

        return false;
    }
}
