<?php

namespace App\Presentation\Http\Controllers\Api;

use App\Presentation\Http\Controllers\Controller;
use App\Domain\Interfaces\Repositories\VideoRepositoryInterface;
use App\Presentation\Http\Resources\Video\VideoResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use App\Infrastructure\Helpers\ApiResponse;

class VideoController extends Controller
{
    public function __construct(
        private VideoRepositoryInterface $videoRepository
    ) {
    }

    /**
     * Get video by ID with full details including related training
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $video = $this->videoRepository->findWithRelations($id);

            return ApiResponse::success(
                new VideoResource($video),
                'Video retrieved successfully'
            );
        } catch (ModelNotFoundException $e) {
            return ApiResponse::error(
                'Video not found',
                null,
                404
            );
        }
    }
}

