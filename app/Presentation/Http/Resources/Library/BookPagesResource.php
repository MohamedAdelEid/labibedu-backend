<?php

namespace App\Presentation\Http\Resources\Library;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookPagesResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'book_id' => $this->resource['book']->id,
            'language' => $this->resource['book']?->language ?? null,
            'total_pages' => $this->resource['pages']->count() ?? 0,
            'last_read_page_id' => $this->resource['last_read_page_id'],
            'examTrainingId' => $this->resource['book']?->related_training_id,
            'cover' => $this->resource['book']?->cover,
            'pages' => PageResource::collection($this->resource['pages']),
        ];
    }
}

