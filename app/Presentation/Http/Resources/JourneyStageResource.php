<?php

namespace App\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JourneyStageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this['id'],
            'type' => $this['type'],
            'total_content' => $this['total_content'],
            'is_locked' => $this['is_locked'],
            'student_stage_data' => [
                'earned_stars' => $this['student_stage_data']['earned_stars'],
                'status' => $this['student_stage_data']['status'],
                'completed_contents' => $this['student_stage_data']['completed_contents'],
            ],
        ];
    }
}

