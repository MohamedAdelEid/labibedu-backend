<?php

namespace App\Presentation\Http\Resources\Assignment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoAssignmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $assignment = $this->resource;
        // Use assignable polymorphic relationship or fallback to video
        $video = $assignment->assignable ?? $assignment->video;
        $videoProgress = null; // Would need to be loaded separately
        $relatedTraining = $video?->relatedTraining;
        $trainingAttempt = null; // Would need to be loaded separately
        $trainingPerformance = null; // Would need to be calculated separately
        $performance = null; // Would need to be calculated separately

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
            'video_details' => [
                'id' => $video->id,
                'title' => [
                    'ar' => $video->title_ar,
                    'en' => $video->title_en,
                ],
                'url' => $video->url,
                'duration' => $video->duration,
                'cover' => $video->cover,
                'video_progress' => $this->formatVideoProgress($videoProgress),
                'related_training' => $this->formatRelatedTraining($relatedTraining, $trainingAttempt, $trainingPerformance),
            ],
            'actual' => [
                'marks' => ($video->marks ?? 0) + ($relatedTraining ? $relatedTraining->getTotalMarks() : 0),
                'xp' => ($video->xp ?? 0) + ($relatedTraining ? $relatedTraining->getTotalXp() : 0),
                'coins' => ($video->coins ?? 0) + ($relatedTraining ? $relatedTraining->getTotalCoins() : 0),
            ],
        ];

        if ($pivotStatus === 'completed' && $performance) {
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
            'title' => [
                'ar' => $training->title_ar ?? $training->title,
                'en' => $training->title_en ?? $training->title,
            ],
            'type' => $training->type->value,
            'questions_count' => $training->questions_count ?? 0,
            'duration_minutes' => $training->duration ?? 0,
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
