<?php

namespace App\Presentation\Http\Resources\Lesson;

use App\Presentation\Http\Resources\Library\BookResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'train_id' => $this->train_id,
            'category' => $this->whenLoaded('category', function () {
                return $this->category ? [
                    'id' => $this->category->id,
                    'name_ar' => $this->category->name_ar,
                    'name_en' => $this->category->name_en,
                ] : null;
            }),
            'books_count' => $this->books_count ?? 0,
            'videos_count' => $this->videos_count ?? 0,
            'books' => BookResource::collection($this->whenLoaded('books')),
            'videos' => $this->whenLoaded('videos', function () {
                return $this->videos->map(function ($video) {
                    return [
                        'id' => $video->id,
                        'title_ar' => $video->title_ar,
                        'title_en' => $video->title_en,
                        'url' => $video->url,
                        'cover' => $video->cover,
                        'subject' => $video->subject ? [
                            'id' => $video->subject->id,
                            'name' => $video->subject->name,
                        ] : null,
                    ];
                });
            }),
        ];
    }
}

