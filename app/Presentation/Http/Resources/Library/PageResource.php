<?php

namespace App\Presentation\Http\Resources\Library;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'image' => $this->image,
            'mp3' => $this->mp3,
            'is_text_to_speech' => $this->is_text_to_speech,
        ];
    }
}

