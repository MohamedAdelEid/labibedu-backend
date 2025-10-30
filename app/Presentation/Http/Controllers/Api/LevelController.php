<?php

namespace App\Presentation\Http\Controllers\Api;

use App\Domain\Interfaces\Repositories\LevelRepositoryInterface;
use App\Infrastructure\Helpers\ApiResponse;
use App\Presentation\Http\Resources\Library\LevelResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class LevelController extends Controller
{
    public function __construct(
        private LevelRepositoryInterface $levelRepository
    ) {
    }

    /**
     * Get all levels (public endpoint)
     */
    public function index(): JsonResponse
    {
        $levels = $this->levelRepository->all();

        return ApiResponse::success(
            LevelResource::collection($levels),
            'Levels retrieved successfully.'
        );
    }
}

