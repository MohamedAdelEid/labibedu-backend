<?php

namespace App\Application\DTOs\AvatarCategory;

use Illuminate\Http\Request;

class CreateAvatarCategoryDTO
{
    public function __construct(
        public string $name_en,
        public string $name_ar,
        public bool $is_active = true,
        public int $sort_order = 0,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            name_en: $request->input('name_en'),
            name_ar: $request->input('name_ar'),
            is_active: $request->input('is_active', true),
            sort_order: $request->input('sort_order', 0),
        );
    }
}
