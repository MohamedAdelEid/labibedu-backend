<?php

namespace App\Presentation\Http\Resources\Assignment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookAssignmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $assignment = $this->resource;
        $book = $assignment->assignable ?? $assignment->book;
        $bookProgress = null;
        $relatedTraining = $book->relatedTraining;
        $trainingPerformance = $this->performance ?? $this->resource['performance'] ?? null;
        $performance = $this->performance ?? $this->resource['performance'] ?? null;

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
            'book_details' => [
                'id' => $book->id,
                'title' => $book->title,
                'cover' => $book->cover ?? null,
                'language' => $book->language ?? null,
                'total_pages' => $book->pages->count(),
                'read_progress' => $this->formatBookProgress($bookProgress),
                'related_training' => $this->formatRelatedTraining($relatedTraining, $trainingPerformance),
            ],
            'actual' => [
                'marks' => $book->marks + ($relatedTraining ? $relatedTraining->getTotalMarks() : 0),
                'xp' => $book->xp + ($relatedTraining ? $relatedTraining->getTotalXp() : 0),
                'coins' => $book->coins + ($relatedTraining ? $relatedTraining->getTotalCoins() : 0),
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

    private function formatBookProgress($progress): ?array
    {
        if (!$progress) {
            return null;
        }

        return [
            'status' => $progress->last_read_page_id ? 'in_progress' : 'not_started',
            'is_favorite' => $progress->is_favorite ?? false,
        ];
    }

    private function formatRelatedTraining($training, $performance): ?array
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
}
