<?php

namespace App\Presentation\Http\Resources\Student;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentSettingsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'language' => $this->language?->value ?? 'ar',
            'theme' => $this->theme?->value ?? 'minimal',
            'notifications_enabled' => $this->notifications_enabled ?? true,
            'haptic_feedback_enabled' => $this->haptic_feedback_enabled ?? true,
            'is_first_time' => $this->is_first_time ?? true,
        ];
    }
}

