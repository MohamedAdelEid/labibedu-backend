<?php

namespace App\Presentation\Http\Resources\Answer;

use Illuminate\Http\Resources\Json\JsonResource;

class AnswerResource extends JsonResource
{
    protected $examTraining;

    public function __construct($resource, $examTraining = null)
    {
        parent::__construct($resource);
        $this->examTraining = $examTraining;
    }

    public function toArray($request): array
    {
        $response = [
            'answer' => $this->resource['answer'],
        ];

        // For training: include XP and coins, exclude remaining_seconds
        if ($this->examTraining && $this->examTraining->isTraining()) {
            $response['is_correct'] = $this->resource['is_correct'];
            $response['gained_xp'] = $this->resource['gained_xp'];
            $response['gained_coins'] = $this->resource['gained_coins'];
        }

        // For exam: include remaining_seconds, exclude XP and coins
        if ($this->examTraining && $this->examTraining->isExam()) {
            $response['remaining_seconds'] = $this->resource['remaining_seconds'];
        }

        return $response;
    }
}
