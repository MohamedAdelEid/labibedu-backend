<?php

namespace App\Presentation\Http\Resources\Assignment;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookAssignmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $assignment = $this->resource;
        $book = $assignment->book;
        $bookProgress = null; // Would need to be loaded separately
        $relatedTraining = $book?->relatedTraining;
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
                'marks' => ($book->marks ?? 0) + ($relatedTraining->marks ?? 0),
                'xp' => ($book->xp ?? 0) + ($relatedTraining->xp ?? 0),
                'coin' => ($book->coins ?? 0) + ($relatedTraining->coins ?? 0),
            ],
            'book_details' => [
                'id' => $book->id,
                'title' => $book->title,
                'total_pages' => $book->total_pages ?? 0,
                'marks' => $book->marks ?? 0,
                'xp' => $book->xp ?? 0,
                'coin' => $book->coins ?? 0,
                'read_progress' => $this->formatBookProgress($bookProgress),
                'related_training' => $this->formatRelatedTraining($relatedTraining, $trainingPerformance),
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

    private function formatBookProgress($progress): ?array
    {
        if (!$progress) {
            return null;
        }

        // StudentBook model only has last_read_page_id and is_favorite
        // Progress calculation would need to be done separately using BookProgressService
        // For now, return basic info if progress exists
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
            'title' => $training->title,
            'type' => $training->type->value,
            'marks' => $training->marks ?? 0,
            'xp' => $training->xp ?? 0,
            'coin' => $training->coins ?? 0,
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
