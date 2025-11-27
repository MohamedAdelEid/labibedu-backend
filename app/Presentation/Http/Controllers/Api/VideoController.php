<?php

namespace App\Presentation\Http\Controllers\Api;

use App\Presentation\Http\Controllers\Controller;
use App\Domain\Interfaces\Repositories\VideoRepositoryInterface;
use App\Presentation\Http\Resources\Video\VideoResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

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

            return response()->json([
                'success' => true,
                'message' => 'Video retrieved successfully',
                'data' => new VideoResource($video),
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Video not found',
            ], 404);
        }
    }
}

