<?php

namespace App\Presentation\Http\Resources\Journey;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JourneyLevelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     * 
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name, // Uses getNameAttribute() for localization
            'completion_percentage' => $this->completion_percentage ?? 0,
            'status' => $this->calculated_status ?? 'locked',
            'stages' => JourneyStageResource::collection($this->stages),
        ];
    }
}

