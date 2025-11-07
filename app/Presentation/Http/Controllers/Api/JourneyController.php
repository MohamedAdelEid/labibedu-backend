<?php

namespace App\Presentation\Http\Controllers\Api;

use App\Infrastructure\Facades\JourneyFacade;
use App\Infrastructure\Helpers\ApiResponse;
use App\Presentation\Http\Controllers\Controller;
use App\Presentation\Http\Resources\JourneyLevelResource;
use Illuminate\Http\JsonResponse;
use Exception;

class JourneyController extends Controller
{
    public function __construct(
        private JourneyFacade $journeyFacade
    ) {
    }

    /**
     * Get student's journey with levels, stages, and progress
     * 
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $studentId = auth()->user()->student->id;

            $journeyData = $this->journeyFacade->getStudentJourney($studentId);

            return ApiResponse::success(
                JourneyLevelResource::collection($journeyData),
                __('messages.success')
            );
        } catch (Exception $e) {
            return ApiResponse::error(
                $e->getMessage(),
                null,
                500
            );
        }
    }
}

