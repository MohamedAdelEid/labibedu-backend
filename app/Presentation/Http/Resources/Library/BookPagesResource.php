<?php

namespace App\Presentation\Http\Resources\Library;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookPagesResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'book_id' => $this->resource['book_id'],
            'last_read_page_id' => $this->resource['last_read_page_id'],
            'pages' => PageResource::collection($this->resource['pages']),
        ];
    }
}

