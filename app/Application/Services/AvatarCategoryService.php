<?php

namespace App\Application\Services;

use App\Application\Exceptions\Avatar\AvatarNotFoundException;
use App\Domain\Interfaces\Repositories\AvatarCategoryRepositoryInterface;
use App\Domain\Interfaces\Services\AvatarCategoryServiceInterface;
use App\Infrastructure\Models\AvatarCategory;
use Illuminate\Support\Collection;

class AvatarCategoryService implements AvatarCategoryServiceInterface
{
    public function __construct(
        private AvatarCategoryRepositoryInterface $avatarCategoryRepository
    ) {
    }

    /**
     * Get all avatar categories
     */
    public function getAllCategories(): Collection
    {
        return $this->avatarCategoryRepository->getAll();
    }

    /**
     * Get active avatar categories only
     */
    public function getActiveCategories(): Collection
    {
        return $this->avatarCategoryRepository->getActive();
    }

    /**
     * Get avatar category by ID
     */
    public function getCategoryById(int $id): AvatarCategory
    {
        $category = $this->avatarCategoryRepository->findById($id);

        if (!$category) {
            throw new AvatarNotFoundException(__('exceptions.avatar_category.not_found'));
        }

        return $category;
    }

    /**
     * Create a new avatar category
     */
    public function createCategory(array $data): AvatarCategory
    {
        return $this->avatarCategoryRepository->create($data);
    }

    /**
     * Update avatar category
     */
    public function updateCategory(int $id, array $data): AvatarCategory
    {
        $category = $this->getCategoryById($id);
        return $this->avatarCategoryRepository->update($category, $data);
    }

    /**
     * Delete avatar category
     */
    public function deleteCategory(int $id): bool
    {
        $category = $this->getCategoryById($id);
        return $this->avatarCategoryRepository->delete($category);
    }

    /**
     * Get categories with avatar count
     */
    public function getCategoriesWithAvatarCount(): Collection
    {
        return $this->avatarCategoryRepository->getWithAvatarCount();
    }
}
