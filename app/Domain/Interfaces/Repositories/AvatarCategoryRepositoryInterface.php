<?php

namespace App\Domain\Interfaces\Repositories;

use App\Infrastructure\Models\AvatarCategory;
use Illuminate\Support\Collection;

interface AvatarCategoryRepositoryInterface
{
    /**
     * Get all avatar categories
     */
    public function getAll(): Collection;

    /**
     * Get active avatar categories only
     */
    public function getActive(): Collection;

    /**
     * Get avatar category by ID
     */
    public function findById(int $id): ?AvatarCategory;

    /**
     * Get avatar category by name
     */
    public function findByName(string $name): ?AvatarCategory;

    /**
     * Create a new avatar category
     */
    public function create(array $data): AvatarCategory;

    /**
     * Update avatar category
     */
    public function update(AvatarCategory $category, array $data): AvatarCategory;

    /**
     * Delete avatar category
     */
    public function delete(AvatarCategory $category): bool;

    /**
     * Get categories with avatar count
     */
    public function getWithAvatarCount(): Collection;
}
