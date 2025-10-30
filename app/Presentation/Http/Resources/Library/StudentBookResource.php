<?php

namespace App\Presentation\Http\Resources\Library;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentBookResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'book_id' => $this->book_id,
            'is_favorite' => $this->is_favorite,
            'last_read_page_id' => $this->last_read_page_id,
        ];
    }
}

