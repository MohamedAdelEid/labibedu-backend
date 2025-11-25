<?php

namespace App\Presentation\Http\Resources\Assignment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamTrainingAssignmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $assignment = $this;
        // Use assignable polymorphic relationship or fallback to examTraining
        $examTraining = $assignment->assignable ?? $assignment->examTraining;
        $attempt = $this->attempt ?? null;
        $performance = $this->resource['performance'] ?? null;
        
        // Get pivot status
        $pivotStatus = $assignment->students->first()?->pivot->status ?? 'not_started';

        $data = [
            'id' => $assignment->id,
            'title' => [
                'ar' => $assignment->title_ar,
                'en' => $assignment->title_en,
            ],
            'type' => $assignment->assignable_type,
            'status' => $pivotStatus,
            'period' => [
                'start_date' => $assignment->start_date?->toIso8601String(),
                'end_date' => $assignment->end_date?->toIso8601String(),
            ],
            'exam_details' => [
                'id' => $examTraining->id,
                'title' => [
                    'ar' => $examTraining->title_ar ?? $examTraining->title,
                    'en' => $examTraining->title_en ?? $examTraining->title,
                ],
                'description' => $examTraining->description,
                'duration_minutes' => $examTraining->duration,
                'questions_count' => $examTraining->questions_count ?? 0,
            ],
        ];

        // Add rewards and result only if completed
        if ($pivotStatus === 'completed' && $performance) {
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
