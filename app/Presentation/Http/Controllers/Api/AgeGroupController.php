<?php

namespace App\Presentation\Http\Controllers\Api;

use App\Domain\Interfaces\Repositories\AgeGroupRepositoryInterface;
use App\Infrastructure\Helpers\ApiResponse;
use App\Presentation\Http\Resources\AgeGroup\AgeGroupResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

class AgeGroupController extends Controller
{
    public function __construct(
        private AgeGroupRepositoryInterface $ageGroupRepository
    ) {
    }

    /**
     * Get all age groups
     */
    public function index(): JsonResponse
    {
        $ageGroups = $this->ageGroupRepository->all();

        return ApiResponse::success(
            AgeGroupResource::collection($ageGroups),
            'Age groups retrieved successfully.'
        );
    }
}

