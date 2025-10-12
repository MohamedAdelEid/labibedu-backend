<?php

namespace App\Presentation\Http\Resources\Exam;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamPerformanceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        if (!$this->resource) {
            return [];
        }

        return [
            'earned_marks' => $this->earned_marks ?? 0,
            'earned_xp' => $this->earned_xp ?? 0,
            'earned_coins' => $this->earned_coins ?? 0,
            'correct_answers' => $this->correct_answers ?? 0,
            'wrong_answers' => $this->wrong_answers ?? 0,
            'unanswered' => $this->unanswered ?? 0,
        ];
    }
}
