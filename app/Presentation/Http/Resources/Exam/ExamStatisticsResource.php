<?php

namespace App\Presentation\Http\Resources\Exam;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamStatisticsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $examTraining = $this->resource['examTraining'];
        $attempt = $this->resource['attempt'];

        return [
            'exam' => [
                'id' => $examTraining->id,
                'title' => $examTraining->title,
                'type' => $examTraining->type->value,
            ],
            'total_questions' => $this->resource['total_questions'],
            'correct_answers' => $this->resource['correct_answers'],
            'score_percentage' => $this->resource['score_percentage'],
            'time_spent_seconds' => $this->resource['time_spent_seconds'],
            'earned_xp' => $this->resource['earned_xp'],
            'earned_coins' => $this->resource['earned_coins'],
            'started_at' => $attempt->start_time?->toIso8601String(),
            'completed_at' => $attempt->end_time?->toIso8601String(),
        ];
    }
}

