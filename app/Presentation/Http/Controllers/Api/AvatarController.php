<?php

namespace App\Presentation\Http\Controllers\Api;

use App\Application\DTOs\Avatar\CreateAvatarDTO;
use App\Domain\Interfaces\Services\AvatarServiceInterface;
use App\Presentation\Http\Controllers\Controller;
use App\Presentation\Http\Resources\Avatar\AvatarResource;
use App\Infrastructure\Helpers\ApiResponse;
use App\Presentation\Http\Requests\Avater\CreateAvatarRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AvatarController extends Controller
{
    public function __construct(
        private AvatarServiceInterface $avatarService
    ) {
    }

    /**
     * Get all available avatars
     */
    public function getAvatars(): JsonResponse
    {
        try {
            $avatars = $this->avatarService->getAllAvatars();

            return ApiResponse::success(AvatarResource::collection($avatars), 'Avatars retrieved successfully');
        } catch (\Exception $e) {
            return ApiResponse::error(
                $e->getMessage(),
                null,
                500
            );
        }
    }

    /**
     * Get all avatars with student-specific information
     */
    public function getAvatarsForStudent(): JsonResponse
    {
        try {
            $user = Auth::user();

            $avatars = $this->avatarService->getAllAvatarsForStudent($user->student->id);

            return ApiResponse::success(
                AvatarResource::collection($avatars),
                'Avatars retrieved successfully'
            );
        } catch (\Exception $e) {
            return ApiResponse::error(
                $e->getMessage(),
                null,
                500
            );
        }
    }

    /**
     * Upload a new avatar
     */
    public function createAvatar(CreateAvatarRequest $request): JsonResponse
    {
        try {
            $dto = CreateAvatarDTO::fromRequest($request);
            $avatar = $this->avatarService->createAvatar($dto);
            return ApiResponse::success(new AvatarResource($avatar), 'Avatar uploaded successfully');
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), null, 500);
        }
    }

    /**
     * Get student's owned avatars
     */
    public function getOwnedAvatars(): JsonResponse
    {
        try {
            $user = Auth::user();

            $avatars = $this->avatarService->getOwnedAvatars($user->student->id);

            return ApiResponse::success(
                AvatarResource::collection($avatars),
                'Owned avatars retrieved successfully'
            );
        } catch (\Exception $e) {
            return ApiResponse::error(
                $e->getMessage(),
                null,
                500
            );
        }
    }

    /**
     * Purchase an avatar
     */
    public function purchaseAvatar(Request $request): JsonResponse
    {
        $request->validate([
            'avatar_id' => 'required|integer|exists:avatars,id',
        ]);

        $user = Auth::user();
        $result = $this->avatarService->purchaseAvatar($user->student->id, $request->avatar_id);

        return ApiResponse::success(
            new AvatarResource($result),
            'Avatar purchased successfully'
        );
    }

    /**
     * Set active avatar
     */
    public function setActiveAvatar(Request $request): JsonResponse
    {
        $user = Auth::user();

        $request->validate([
            'avatar_id' => 'required|integer|exists:avatars,id',
        ]);

        $result = $this->avatarService->setActiveAvatar($user->student->id, $request->avatar_id);

        return ApiResponse::success(
            new AvatarResource($result),
            'Active avatar updated successfully'
        );
    }

    /**
     * Get avatars by category
     */
    public function getAvatarsByCategory(Request $request, int $categoryId): JsonResponse
    {
        $avatars = $this->avatarService->getAvatarsByCategory($categoryId);

        return ApiResponse::success(
            AvatarResource::collection($avatars),
            'Avatars by category retrieved successfully'
        );
    }

    /**
     * Get avatars grouped by category
     */
    public function getAvatarsGroupedByCategory(): JsonResponse
    {
        try {
            $user = Auth::user();
            $studentId = $user->student?->id;

            $groupedAvatars = $this->avatarService->getAvatarsGroupedByCategory($studentId);

            // Transform the grouped collection into a proper format
            $formattedData = $groupedAvatars->map(function ($avatars, $categoryName) {
                return [
                    'category_name' => $categoryName,
                    'avatars' => AvatarResource::collection($avatars)
                ];
            });

            return ApiResponse::success(
                $formattedData,
                'Avatars grouped by category retrieved successfully'
            );
        } catch (\Exception $e) {
            return ApiResponse::error(
                $e->getMessage(),
                null,
                500
            );
        }
    }
}
