<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Interfaces\Repositories\AvatarCategoryRepositoryInterface;
use App\Infrastructure\Models\AvatarCategory;
use Illuminate\Support\Collection;

class AvatarCategoryRepository implements AvatarCategoryRepositoryInterface
{
    /**
     * Get all avatar categories
     */
    public function getAll(): Collection
    {
        return AvatarCategory::ordered()->get();
    }

    /**
     * Get active avatar categories only
     */
    public function getActive(): Collection
    {
        return AvatarCategory::active()->ordered()->get();
    }

    /**
     * Get avatar category by ID
     */
    public function findById(int $id): ?AvatarCategory
    {
        return AvatarCategory::find($id);
    }

    /**
     * Get avatar category by name (English)
     */
    public function findByName(string $name): ?AvatarCategory
    {
        return AvatarCategory::where('name_en', $name)->first();
    }

    /**
     * Create a new avatar category
     */
    public function create(array $data): AvatarCategory
    {
        return AvatarCategory::create($data);
    }

    /**
     * Update avatar category
     */
    public function update(AvatarCategory $category, array $data): AvatarCategory
    {
        $category->update($data);
        return $category->fresh();
    }

    /**
     * Delete avatar category
     */
    public function delete(AvatarCategory $category): bool
    {
        return $category->delete();
    }

    /**
     * Get categories with avatar count
     */
    public function getWithAvatarCount(): Collection
    {
        return AvatarCategory::withCount('avatars')
            ->active()
            ->ordered()
            ->get();
    }
}
