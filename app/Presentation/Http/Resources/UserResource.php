<?php

namespace App\Presentation\Http\Resources;

use App\Presentation\Http\Resources\Avatar\AvatarResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'userName' => $this->user_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => $this->role->value,
            'createdAt' => $this->created_at->toIso8601String(),
        ];

        // Add student-specific data if user is a student
        if ($this->student) {
            $data['coins'] = $this->student->coins ?? 0;
            $data['xp'] = $this->student->xp ?? 0;
            $data['activeAvatar'] = $this->student->activeAvatar
                ? new AvatarResource($this->student->activeAvatar)
                : null;
            $data['isFirstTime'] = $this->student->is_first_time ?? true;
            $data['currentStep'] = $this->getCurrentStep();
            $data['language'] = $this->student->language?->value ?? 'ar';
        }

        return $data;
    }

    /**
     * Calculate the current onboarding step for first-time users
     */
    private function getCurrentStep(): int
    {
        if (!$this->student || !$this->student->is_first_time) {
            return 4; // All steps completed
        }

        // Step 1: Name is required
        if (empty($this->student->name)) {
            return 1;
        }

        // Step 2: Gender is required
        if (empty($this->student->gender)) {
            return 2;
        }

        // Step 3: Age group is required
        if (empty($this->student->age_group_id)) {
            return 3;
        }

        // All steps completed
        return 4;
    }
}