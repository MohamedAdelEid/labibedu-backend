<?php

namespace App\Presentation\Http\Resources\Question;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WrittenQuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'exam_training_id' => $this->exam_training_id,
            'title' => $this->title,
            'type' => $this->type->value,
            'options' => [],
            'user_answer' => $this->user_answer ?? null,
        ];
    }
}