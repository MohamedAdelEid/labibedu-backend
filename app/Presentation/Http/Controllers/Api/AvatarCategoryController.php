<?php

namespace App\Presentation\Http\Controllers\Api;

use App\Application\DTOs\AvatarCategory\CreateAvatarCategoryDTO;
use App\Application\DTOs\AvatarCategory\UpdateAvatarCategoryDTO;
use App\Domain\Interfaces\Services\AvatarCategoryServiceInterface;
use App\Infrastructure\Helpers\ApiResponse;
use App\Presentation\Http\Controllers\Controller;
use App\Presentation\Http\Requests\AvatarCategory\CreateAvatarCategoryRequest;
use App\Presentation\Http\Requests\AvatarCategory\UpdateAvatarCategoryRequest;
use App\Presentation\Http\Resources\AvatarCategory\AvatarCategoryResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AvatarCategoryController extends Controller
{
    public function __construct(
        private AvatarCategoryServiceInterface $avatarCategoryService
    ) {
    }

    /**
     * Get all avatar categories
     */
    public function index(Request $request): JsonResponse
    {
        $activeOnly = $request->boolean('active_only', false);

        $categories = $activeOnly ? $this->avatarCategoryService->getActiveCategories() : $this->avatarCategoryService->getAllCategories();

        return ApiResponse::success(
            AvatarCategoryResource::collection($categories),
            'Avatar categories retrieved successfully'
        );
    }

    /**
     * Get avatar category by ID
     */
    public function show(int $id): JsonResponse
    {
        $category = $this->avatarCategoryService->getCategoryById($id);

        return ApiResponse::success(
            new AvatarCategoryResource($category),
            'Avatar category retrieved successfully'
        );
    }

    /**
     * Create a new avatar category
     */
    public function store(CreateAvatarCategoryRequest $request): JsonResponse
    {
        $dto = CreateAvatarCategoryDTO::fromRequest($request);
        $category = $this->avatarCategoryService->createCategory([
            'name_en' => $dto->name_en,
            'name_ar' => $dto->name_ar,
            'is_active' => $dto->is_active,
            'sort_order' => $dto->sort_order,
        ]);

        return ApiResponse::success(
            new AvatarCategoryResource($category),
            'Avatar category created successfully',
            201
        );
    }

    /**
     * Update avatar category
     */
    public function update(UpdateAvatarCategoryRequest $request, int $id): JsonResponse
    {
        $dto = UpdateAvatarCategoryDTO::fromRequest($request);
        $category = $this->avatarCategoryService->updateCategory($id, $dto->toArray());

        return ApiResponse::success(
            new AvatarCategoryResource($category),
            'Avatar category updated successfully'
        );
    }

    /**
     * Delete avatar category
     */
    public function destroy(int $id): JsonResponse
    {
        $this->avatarCategoryService->deleteCategory($id);

        return ApiResponse::success(
            null,
            'Avatar category deleted successfully'
        );
    }

    /**
     * Get categories with avatar count
     */
    public function withAvatarCount(): JsonResponse
    {
        $categories = $this->avatarCategoryService->getCategoriesWithAvatarCount();

        return ApiResponse::success(
            AvatarCategoryResource::collection($categories),
            'Avatar categories with count retrieved successfully'
        );
    }
}
