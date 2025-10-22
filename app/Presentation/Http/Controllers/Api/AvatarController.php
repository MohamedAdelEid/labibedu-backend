<?php

namespace App\Presentation\Http\Controllers\Api;

use App\Domain\Interfaces\Services\AvatarServiceInterface;
use App\Presentation\Http\Controllers\Controller;
use App\Infrastructure\Helpers\ApiResponse;
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

            return ApiResponse::success(
                $avatars,
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
     * Get student's owned avatars
     */
    public function getOwnedAvatars(): JsonResponse
    {
        $user = Auth::user();
        if (!$user || !$user->student) {
            return ApiResponse::error(
                'Student access required',
                null,
                403
            );
        }

        try {
            $avatars = $this->avatarService->getOwnedAvatars($user->student->id);

            return ApiResponse::success(
                $avatars,
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
        $user = Auth::user();
        if (!$user || !$user->student) {
            return ApiResponse::error(
                'Student access required',
                null,
                403
            );
        }

        $request->validate([
            'avatar_id' => 'required|integer|exists:avatars,id',
        ]);

        try {
            $result = $this->avatarService->purchaseAvatar($user->student->id, $request->avatar_id);

            if ($result['success']) {
                return ApiResponse::success(
                    $result['avatar'],
                    'Avatar purchased successfully'
                );
            } else {
                return ApiResponse::error(
                    $result['message'],
                    null,
                    400
                );
            }
        } catch (\Exception $e) {
            return ApiResponse::error(
                $e->getMessage(),
                null,
                500
            );
        }
    }

    /**
     * Set active avatar
     */
    public function setActiveAvatar(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user || !$user->student) {
            return ApiResponse::error(
                'Student access required',
                null,
                403
            );
        }

        $request->validate([
            'avatar_id' => 'required|integer|exists:avatars,id',
        ]);

        try {
            $result = $this->avatarService->setActiveAvatar($user->student->id, $request->avatar_id);

            if ($result['success']) {
                return ApiResponse::success(
                    $result['avatar'],
                    'Active avatar updated successfully'
                );
            } else {
                return ApiResponse::error(
                    $result['message'],
                    null,
                    400
                );
            }
        } catch (\Exception $e) {
            return ApiResponse::error(
                $e->getMessage(),
                null,
                500
            );
        }
    }
}
