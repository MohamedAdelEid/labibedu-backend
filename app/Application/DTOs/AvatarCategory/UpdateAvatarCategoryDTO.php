<?php

namespace App\Application\DTOs\AvatarCategory;

use Illuminate\Http\Request;

class UpdateAvatarCategoryDTO
{
    public function __construct(
        public ?string $name_en,
        public ?string $name_ar,
        public ?bool $is_active,
        public ?int $sort_order,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            name_en: $request->input('name_en'),
            name_ar: $request->input('name_ar'),
            is_active: $request->input('is_active'),
            sort_order: $request->input('sort_order'),
        );
    }

    /**
     * Get only filled data (non-null values)
     */
    public function toArray(): array
    {
        return array_filter([
            'name_en' => $this->name_en,
            'name_ar' => $this->name_ar,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
        ], fn($value) => $value !== null);
    }
}
