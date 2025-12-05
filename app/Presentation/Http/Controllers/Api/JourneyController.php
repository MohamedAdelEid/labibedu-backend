<?php

namespace App\Presentation\Http\Controllers\Api;

use App\Application\DTOs\Journey\GetJourneyDTO;
use App\Application\UseCases\Journey\GetJourneyUseCase;
use App\Infrastructure\Facades\JourneyFacade;
use App\Infrastructure\Helpers\ApiResponse;
use App\Presentation\Http\Controllers\Controller;
use App\Presentation\Http\Resources\Journey\JourneyDataResource;
use App\Presentation\Http\Resources\Journey\JourneyLevelResource;
use Illuminate\Http\JsonResponse;
use Exception;

class JourneyController extends Controller
{
    public function __construct(
        private JourneyFacade $journeyFacade,
        private GetJourneyUseCase $getJourneyUseCase
    ) {
    }

    /**
     * Get student's journey with levels, stages, and progress
     * 
     * GET /api/student/journey
     * 
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $studentId = auth()->user()->student->id;

            // Facade returns Eloquent models/collections
            $levels = $this->journeyFacade->getStudentJourney($studentId);

            // Resources format the JSON
            return ApiResponse::success(
                JourneyLevelResource::collection($levels),
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

    public function getJourneyData(): JsonResponse
    {
        try {
            $studentId = auth()->user()->student->id;
            $dto = GetJourneyDTO::fromRequest($studentId);
            $data = $this->getJourneyUseCase->execute($dto);

            return ApiResponse::success(
                new JourneyDataResource($data),
                'تم جلب بيانات الرحلة بنجاح'
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
