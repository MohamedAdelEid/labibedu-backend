<?php

namespace App\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamTrainingDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type->value,
            'duration' => $this->duration,
            'startDate' => $this->start_date?->toIso8601String(),
            'endDate' => $this->end_date?->toIso8601String(),
            'lockedAfterDuration' => $this->locked_after_duration?->toIso8601String(),
            'subject' => [
                'id' => $this->subject->id,
                'name' => $this->subject->name,
            ],
            'video' => $this->video ? [
                'id' => $this->video->id,
                'title' => $this->video->title,
                'url' => $this->video->url,
            ] : null,
            'book' => $this->book ? [
                'id' => $this->book->id,
                'title' => $this->book->title,
                'url' => $this->book->url,
            ] : null,
            'createdAt' => $this->created_at->toIso8601String(),
        ];
    }
}