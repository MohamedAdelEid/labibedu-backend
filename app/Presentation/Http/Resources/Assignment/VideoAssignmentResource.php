<?php

namespace App\Presentation\Http\Resources\Assignment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoAssignmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $assignment = $this->resource;
        $video = $assignment->video;
        $videoProgress = null; // Would need to be loaded separately
        $relatedTraining = $video?->relatedTraining;
        $trainingAttempt = null; // Would need to be loaded separately
        $trainingPerformance = null; // Would need to be calculated separately
        $performance = null; // Would need to be calculated separately

        $data = [
            'id' => $assignment->id,
            'title' => $assignment->title,
            'type' => $assignment->type,
            'status' => $assignment->pivot->status ?? 'not_submitted',
            'period' => [
                'start_date' => $assignment->start_date?->toIso8601String(),
                'end_date' => $assignment->end_date?->toIso8601String(),
            ],
            'assignment_rewards' => [
                'marks' => ($relatedTraining->marks ?? 0),
                'xp' => ($relatedTraining->xp ?? 0),
                'coin' => ($relatedTraining->coins ?? 0),
            ],
            'video_details' => [
                'id' => $video->id,
                'title_ar' => $video->title_ar,
                'title_en' => $video->title_en,
                'url' => $video->url,
                'cover' => $video->cover,
                'video_progress' => $this->formatVideoProgress($videoProgress),
                'related_training' => $this->formatRelatedTraining($relatedTraining, $trainingAttempt, $trainingPerformance),
            ],
        ];

        if ($performance) {
            $data['result'] = [
                'earned_marks' => $performance['earned_marks'] ?? 0,
                'earned_xp' => $performance['earned_xp'] ?? 0,
                'earned_coins' => $performance['earned_coins'] ?? 0,
            ];
        } else {
            $data['result'] = null;
        }

        return $data;
    }

    private function formatVideoProgress($progress): ?array
    {
        if (!$progress) {
            return null;
        }

        return [
            'watched_seconds' => $progress->watched_seconds ?? 0,
            'progress_percent' => $progress->progress_percent ?? 0,
            'is_completed' => $progress->is_completed ?? false,
            'last_watched_at' => $progress->last_watched_at?->toIso8601String(),
        ];
    }

    private function formatRelatedTraining($training, $attempt, $performance): ?array
    {
        if (!$training) {
            return null;
        }

        $data = [
            'id' => $training->id,
            'title' => $training->title,
            'type' => $training->type->value,
            'marks' => $training->marks ?? 0,
            'xp' => $training->xp ?? 0,
            'coin' => $training->coins ?? 0,
            'attempt_info' => $this->formatAttemptInfo($attempt),
        ];

        if ($performance) {
            $data['result'] = [
                'earned_marks' => $performance['earned_marks'] ?? 0,
                'earned_xp' => $performance['earned_xp'] ?? 0,
                'earned_coins' => $performance['earned_coins'] ?? 0,
            ];
        } else {
            $data['result'] = null;
        }

        return $data;
    }

    private function formatAttemptInfo($attempt): ?array
    {
        if (!$attempt) {
            return null;
        }

        return [
            'status' => $attempt->status->value,
            'remaining_seconds' => $attempt->remaining_seconds ?? 0,
            'start_time' => $attempt->start_time?->toIso8601String(),
            'end_time' => $attempt->end_time?->toIso8601String(),
        ];
    }
}
