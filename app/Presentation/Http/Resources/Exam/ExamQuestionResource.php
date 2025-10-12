<?php

namespace App\Presentation\Http\Resources\Exam;

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
        if ($question->type->value === 'connect') {
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

        if ($question->type->value === 'true_false') {
            return null;
        }

        return $question->options->map(fn($opt) => [
            'id' => $opt->id,
            'text' => $opt->text,
            'image' => $opt->image,
        ])->values();
    }
}
