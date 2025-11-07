<?php

namespace App\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JourneyLevelResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this['id'],
            'name' => $this['name'],
            'completion_percentage' => $this['completion_percentage'],
            'status' => $this['status'],
            'stages' => JourneyStageResource::collection($this['stages']),
        ];
    }
}

