<?php

namespace App\Presentation\Http\Resources\Journey;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JourneyStageResource extends JsonResource
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
            'type' => $this->type,
            'total_content' => $this->total_content ?? 0,
            'is_locked' => !($this->is_open ?? false), // Invert is_open to is_locked for API
            'student_stage_data' => [
                'earned_stars' => $this->earned_stars ?? 0,
                'status' => $this->calculated_status ?? 'not_started',
                'completed_contents' => $this->completed_contents ?? 0,
            ],
        ];
    }
}

