<?php

namespace App\Presentation\Http\Controllers\Api;

use App\Application\Services\AssignmentService;
use App\Infrastructure\Helpers\ApiResponse;
use App\Presentation\Http\Resources\Assignment\AssignmentResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class AssignmentController extends Controller
{
    public function __construct(
        private AssignmentService $assignmentService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'type' => 'nullable|in:current,exams,reading,watching',
            'status' => 'nullable|in:not_started,in_progress,completed,not_submitted',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $studentId = Auth::user()->student->id;
        $type = $request->input('type');
        $status = $request->input('status');
        $perPage = $request->input('per_page', 10);

        $assignments = $this->assignmentService->getAssignments($studentId, $type, $status, $perPage);

        $transformedAssignments = $assignments->through(
            fn($assignment) =>
            new AssignmentResource($assignment)
        );

        return ApiResponse::paginated(
            $transformedAssignments,
            'Assignments retrieved successfully.'
        );
    }
}
