<?php

namespace App\Presentation\Http\Resources\Video;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $video = $this->resource;
        $relatedTraining = $video->relatedTraining;
        
        // Load questions if related training exists
        $questions = $relatedTraining ? $relatedTraining->questions : collect();

        $data = [
            'id' => $video->id,
            'title' => [
                'ar' => $video->title_ar,
                'en' => $video->title_en,
            ],
            'url' => $video->url,
            'cover' => $video->cover,
            'duration' => $video->duration,
            'rewards' => [
                'xp' => $video->xp,
                'coins' => $video->coins,
                'marks' => $video->marks,
            ],
            'subject' => $video->subject ? [
                'id' => $video->subject->id,
                'name_ar' => $video->subject->name_ar ?? $video->subject->name,
                'name_en' => $video->subject->name_en ?? $video->subject->name,
            ] : null,
            'related_training' => $this->formatRelatedTraining($relatedTraining, $questions),
            'created_at' => $video->created_at?->toIso8601String(),
            'updated_at' => $video->updated_at?->toIso8601String(),
        ];

        return $data;
    }

    private function formatRelatedTraining($training, $questions): ?array
    {
        if (!$training) {
            return null;
        }

        return [
            'id' => $training->id,
            'title' => [
                'ar' => $training->title_ar ?? $training->title,
                'en' => $training->title_en ?? $training->title,
            ],
            'description' => [
                'ar' => $training->description_ar ?? $training->description,
                'en' => $training->description_en ?? $training->description,
            ],
            'type' => $training->type->value,
            'duration' => $training->duration,
            'start_date' => $training->start_date?->toIso8601String(),
            'end_date' => $training->end_date?->toIso8601String(),
            'total_rewards' => [
                'xp' => $training->getTotalXp(),
                'coins' => $training->getTotalCoins(),
                'marks' => $training->getTotalMarks(),
            ],
            'questions_count' => $questions->count(),
            'questions' => $this->formatQuestions($questions),
        ];
    }

    private function formatQuestions($questions): array
    {
        return $questions->map(function ($question) {
            return [
                'id' => $question->id,
                'title' => $question->title,
                'type' => $question->type->value ?? null,
                'language' => $question->language ? $question->language->value : 'ar',
                'xp' => $question->xp ?? 0,
                'coins' => $question->coins ?? 0,
                'marks' => $question->marks ?? 0,
                'has_images' => $this->hasImages($question),
                'options' => $this->formatOptions($question),
            ];
        })->toArray();
    }

    private function formatOptions($question): mixed
    {
        $type = $question->type->value;

        if ($type === 'true_false') {
            return [];
        }

        if ($type === 'written') {
            return [];
        }

        if ($type === 'connect') {
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

        // For choice and arrange types
        return $question->options->map(function ($option) {
            return [
                'id' => $option->id,
                'text' => $option->text,
                'image' => $option->image,
            ];
        })->toArray();
    }

    private function hasImages($question): bool
    {
        if (!$question->options || $question->options->isEmpty()) {
            return false;
        }

        return $question->options->contains(function ($option) {
            return !empty($option->image);
        });
    }
}

