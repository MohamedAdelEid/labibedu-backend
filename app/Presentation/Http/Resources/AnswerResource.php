<?php

namespace App\Presentation\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnswerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'questionId' => $this->question_id,
            'isCorrect' => $this->is_correct,
            'gainedXp' => $this->gained_xp,
            'gainedCoins' => $this->gained_coins,
            'submittedAt' => $this->submitted_at->toIso8601String(),
        ];
    }
}