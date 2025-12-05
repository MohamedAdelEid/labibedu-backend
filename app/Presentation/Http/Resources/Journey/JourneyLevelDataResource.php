<?php

namespace App\Presentation\Http\Resources\Journey;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JourneyLevelDataResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource['id'],
            'number' => $this->resource['number'],
            'name_ar' => $this->resource['name_ar'],
            'name_en' => $this->resource['name_en'],
            'progressPercentage' => $this->resource['progressPercentage'],
            'status' => $this->resource['status'],
            'stages' => JourneyStageDataResource::collection($this->resource['stages']),
        ];
    }
}

