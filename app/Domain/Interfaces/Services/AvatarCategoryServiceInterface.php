<?php

namespace App\Domain\Interfaces\Services;

use App\Infrastructure\Models\AvatarCategory;
use Illuminate\Support\Collection;

interface AvatarCategoryServiceInterface
{
    /**
     * Get all avatar categories
     */
    public function getAllCategories(): Collection;

    /**
     * Get active avatar categories only
     */
    public function getActiveCategories(): Collection;

    /**
     * Get avatar category by ID
     */
    public function getCategoryById(int $id): AvatarCategory;

    /**
     * Create a new avatar category
     */
    public function createCategory(array $data): AvatarCategory;

    /**
     * Update avatar category
     */
    public function updateCategory(int $id, array $data): AvatarCategory;

    /**
     * Delete avatar category
     */
    public function deleteCategory(int $id): bool;

    /**
     * Get categories with avatar count
     */
    public function getCategoriesWithAvatarCount(): Collection;
}
