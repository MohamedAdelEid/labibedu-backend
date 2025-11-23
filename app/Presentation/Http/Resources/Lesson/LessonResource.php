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
            'title' => $this->title, // Uses getTitleAttribute() from model (localized)
            'name' => $this->name, // Alias for title (localized)
            'category' => $this->category,
            'books_count' => $this->books_count ?? 0,
            'videos_count' => $this->videos_count ?? 0,
            'books' => BookResource::collection($this->whenLoaded('books')),
            'videos' => $this->whenLoaded('videos', function () {
                return $this->videos->map(function ($video) {
                    return [
                        'id' => $video->id,
                        'title' => $video->title,
                        'url' => $video->url,
                        'duration' => $video->duration,
                        'xp' => $video->xp,
                        'coins' => $video->coins,
                        'marks' => $video->marks,
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

