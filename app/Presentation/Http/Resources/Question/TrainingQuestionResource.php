<?php

namespace App\Presentation\Http\Resources\Question;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TrainingQuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $base = [
            'id' => $this->id,
            'exam_training_id' => $this->exam_training_id,
            'title' => $this->title,
            'type' => $this->type->value,
            'xp' => $this->xp,
            'coins' => $this->coins,
            'options' => $this->formatOptions(),
            'answers' => $this->formatAnswers(),
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
            'arrange' => ArrangeOptionsResource::collection($this->options->shuffle())->resolve(), 
            'written' => [],
            default => [],
        };
    }

    // Format answers based on question type
    private function formatAnswers(): array
    {
        return match ($this->type->value) {
            'choice' => $this->getChoiceAnswer(),
            'true_false' => $this->getTrueFalseAnswer(),
            'connect' => $this->getConnectAnswer(),
            'arrange' => $this->getArrangeAnswer(),
            'written' => [],
            default => [],
        };
    }

    private function getChoiceAnswer(): array
    {
        $correctOption = $this->options->firstWhere('is_correct', true);
        
        return [
            'id' => $correctOption?->id,
            'isCorrect' => true,
        ];
    }

    private function getTrueFalseAnswer(): array
    {
        $correctOption = $this->options->firstWhere('is_correct', true);
        
        return [
            'answer' => $correctOption?->text === 'True',
            'isCorrect' => true,
        ];
    }

    private function getConnectAnswer(): array
    {
        $leftOptions = $this->options->where('side', 'left');
        
        $pairs = $leftOptions->map(function ($leftOption) {
            return [
                'left' => $leftOption->id,
                'right' => $leftOption->match_id,
            ];
        })->values();

        return $pairs->toArray();
    }

    private function getArrangeAnswer(): array
    {
        return $this->options
            ->sortBy('arrange_order')
            ->pluck('id')
            ->values()
            ->toArray();
    }
}