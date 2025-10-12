<?php

namespace App\Presentation\Http\Resources\Assignment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamTrainingAssignmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $assignment = $this;
        // dd($this);
        $examTraining = $this->examTraining;
        $attempt = $this->attempt ?? null;
        $performance = $this->resource['performance'] ?? null;

        $data = [
            'id' => $assignment->id,
            'title' => $assignment->title,
            'type' => $assignment->type,
            'status' => $assignment->pivot->status ?? 'not_submitted',
            'period' => [
                'start_date' => $assignment->start_date?->toIso8601String(),
                'end_date' => $assignment->end_date?->toIso8601String(),
            ],
            'exam_details' => [
                'id' => $examTraining->id,
                'description' => $examTraining->description,
                'duration_minutes' => $examTraining->duration,
                'marks' => $examTraining->marks ?? 0,
                'xp' => $examTraining->xp ?? 0,
                'coin' => $examTraining->coins ?? 0,
                'questions_count' => $examTraining->questions_count ?? 0,
                'attempt_info' => $this->formatAttemptInfo($attempt),
            ],
        ];

        if ($performance) {
            $data['result'] = [
                'earned_marks' => $performance['earned_marks'] ?? 0,
                'earned_xp' => $performance['earned_xp'] ?? 0,
                'earned_coins' => $performance['earned_coins'] ?? 0,
                'correct_answers' => $performance['correct_answers'] ?? 0,
                'wrong_answers' => $performance['wrong_answers'] ?? 0,
                'unanswered' => $performance['unanswered'] ?? 0,
            ];
        } else {
            $data['result'] = null;
        }

        return $data;
    }

    private function formatAttemptInfo($attempt): array
    {
        if (!$attempt) {
            return [
                'status' => 'not_started',
                'remaining_seconds' => null,
                'start_time' => null,
                'end_time' => null,
            ];
        }

        return [
            'status' => $attempt->status->value,
            'remaining_seconds' => $attempt->remaining_seconds ?? 0,
            'start_time' => $attempt->start_time?->toIso8601String(),
            'end_time' => $attempt->end_time?->toIso8601String(),
        ];
    }
}
