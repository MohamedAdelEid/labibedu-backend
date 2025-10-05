<?php

namespace App\Presentation\Http\Resources\Question;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $base = [
            'id' => $this->id,
            'exam_training_id' => $this->exam_training_id,
            'title' => $this->title,
            'type' => $this->type->value,
            'options' => $this->formatOptions(),
        ];

        // Add user_answer for written questions
        if ($this->type->value === 'written') {
            $base['user_answer'] = $this->user_answer ?? null;
        }

        return $base;
    }

    private function formatOptions(): array
    {
        return match ($this->type->value) {
            'choice' => ChoiceOptionsResource::collection($this->options)->resolve(),
            'true_false' => [],
            'connect' => (new ConnectOptionsResource($this->options, true))->resolve(),
            'arrange' => ArrangeOptionsResource::collection($this->options->shuffle())->resolve(), // <CHANGE> Shuffle arrange options
            'written' => [],
            default => [],
        };
    }
}