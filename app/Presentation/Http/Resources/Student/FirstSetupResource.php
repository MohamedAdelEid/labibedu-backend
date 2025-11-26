<?php

namespace App\Presentation\Http\Resources\Student;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FirstSetupResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'age_group' => [
                'id' => $this->ageGroup?->id,
                'name' => $this->ageGroup?->name,
            ],
            'gender' => $this->gender?->value,
            'theme' => $this->theme?->value,
            'language' => $this->language?->value ?? 'ar',
            'level' => $this->getLevel(),
            'xp' => $this->xp,
            'coins' => $this->coins,
            'xp_progress' => $this->getXpProgress(),
            'is_first_time' => $this->is_first_time,
        ];
    }
}

