<?php

namespace App\Presentation\Http\Resources\Avatar;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class AvatarResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'url' => $this->full_url,
            'coins' => $this->coins,
            'category' => $this->whenLoaded('category', function () {
                return [
                    'id' => $this->category->id,
                    'name' => $this->category->name, // Localized name
                    'name_en' => $this->category->name_en,
                    'name_ar' => $this->category->name_ar,
                ];
            }),
        ];

        // Add student-specific data if available
        if (isset($this->is_owned)) {
            $data['is_owned'] = $this->is_owned;
        }

        if (isset($this->is_active)) {
            $data['is_active'] = $this->is_active;
        }

        // Add purchased_at if it's a pivot relationship
        if (isset($this->pivot) && $this->pivot->purchased_at) {
            $data['purchased_at'] = $this->pivot->purchased_at;
        }

        return $data;
    }
}