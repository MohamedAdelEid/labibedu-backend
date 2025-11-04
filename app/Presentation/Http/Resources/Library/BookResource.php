<?php

namespace App\Presentation\Http\Resources\Library;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'cover' => $this->cover,
            'language' => $this->language,
            'xp' => $this->xp,
            'coins' => $this->coins,
            'marks' => $this->marks,
            'total_pages' => $this->pages_count ?? 0,
            'subject' => $this->subject ? new SubjectResource($this->subject) : null,
            'level' => $this->level ? new LevelResource($this->level) : null,
            'is_new' => $this->is_new ?? false,
            'is_locked' => $this->is_locked ?? false,
            'has_sound' => $this->has_sound,
            'has_training' => $this->has_training ?? false,
            'is_favourite' => $this->is_favourite ?? false,
            'reading_status' => $this->reading_status ?? 'not_started',
            'examTrainingId' => $this->related_training_id,
        ];
    }
}


