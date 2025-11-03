<?php

namespace App\Presentation\Http\Resources;

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
            'profileImage' => $this->profile_image,
            'role' => $this->role->value,
            'createdAt' => $this->created_at->toIso8601String(),
        ];

        // Add coins and xp if user is a student
        if ($this->student) {
            $data['coins'] = $this->student->coins ?? 0;
            $data['xp'] = $this->student->xp ?? 0;
        }

        return $data;
    }
}