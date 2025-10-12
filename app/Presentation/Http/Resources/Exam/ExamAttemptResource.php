<?php

namespace App\Presentation\Http\Resources\Exam;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamAttemptResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        if (!$this->resource) {
            return [
                'status' => 'not_started',
                'remaining_seconds' => null,
                'start_time' => null,
                'end_time' => null,
            ];
        }

        return [
            'id' => $this->id,
            'status' => $this->status->value,
            'start_time' => $this->start_time?->toIso8601String(),
            'end_time' => $this->end_time?->toIso8601String(),
            'remaining_seconds' => $this->remaining_seconds,
        ];
    }
}
