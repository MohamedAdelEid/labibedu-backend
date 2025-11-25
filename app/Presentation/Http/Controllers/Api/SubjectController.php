<?php

namespace App\Presentation\Http\Controllers\Api;

use App\Application\DTOs\Subject\GetSubjectsDTO;
use App\Infrastructure\Facades\SubjectFacade;
use App\Infrastructure\Helpers\ApiResponse;
use App\Presentation\Http\Requests\Subject\GetSubjectsRequest;
use App\Presentation\Http\Resources\Library\SubjectResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class SubjectController extends Controller
{
    public function __construct(
        private SubjectFacade $subjectFacade
    ) {
    }

    /**
     * Get subjects by grade_id or user_id
     */
    public function index(GetSubjectsRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['user_id'] = Auth::user()->student->id;

        $dto = GetSubjectsDTO::fromRequest($validated);

        // Get subjects from facade
        $subjects = $this->subjectFacade->getSubjects($dto);

        // Transform subjects using resource
        $subjectsCollection = SubjectResource::collection($subjects);

        return ApiResponse::success($subjectsCollection);
    }
}

