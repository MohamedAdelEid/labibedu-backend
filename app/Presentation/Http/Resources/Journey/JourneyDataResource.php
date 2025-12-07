<?php

namespace App\Presentation\Http\Resources\Journey;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JourneyDataResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'exam' => $this->resource['exam'],
            'assignmentCount' => $this->resource['assignmentCount'],
            // 'assignmentType' => $this->resource['assignmentType'] ?? null,
            'bookCard' => $this->resource['bookCard'],
            'levels' => JourneyLevelDataResource::collection($this->resource['levels']),
        ];
    }
}

