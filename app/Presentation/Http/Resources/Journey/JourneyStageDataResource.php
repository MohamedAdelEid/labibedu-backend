<?php

namespace App\Presentation\Http\Resources\Journey;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JourneyStageDataResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource['id'],
            'type' => $this->resource['type'],
            'status' => $this->resource['status'],
            'starsEarned' => $this->resource['starsEarned'],
            'completedLessons' => $this->resource['completedLessons'],
            'totalLessons' => $this->resource['totalLessons'],
            'data' => $this->resource['data'],
        ];
    }
}

