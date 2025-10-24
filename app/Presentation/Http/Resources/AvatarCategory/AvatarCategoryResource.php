<?php

namespace App\Presentation\Http\Resources\AvatarCategory;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AvatarCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name, // This will return localized name based on current locale
            'name_en' => $this->name_en,
            'name_ar' => $this->name_ar,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
            'avatars_count' => $this->whenCounted('avatars'),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
