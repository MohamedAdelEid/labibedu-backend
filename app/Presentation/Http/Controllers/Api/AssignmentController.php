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
            'type' => 'nullable|in:current,exams,reading,watching,training',
            'status' => 'nullable|in:not_started,in_progress,completed,not_submitted',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $studentId = Auth::user()->student->id;
        $type = $request->input('type');
        $status = $request->input('status');
        $perPage = (int) $request->input('per_page', 10);

        // Get assignments with pagination
        $assignments = $this->assignmentService->getAssignments($studentId, $type, $status, $perPage);

        // Get statistics
        $stats = $this->assignmentService->getAssignmentsStats($studentId);

        // Add performance data for completed assignments
        foreach ($assignments as $assignment) {
            // Get pivot status
            $pivotStatus = $assignment->students->first()?->pivot->status ?? 'not_started';

            // Calculate performance for completed exam/training assignments
            if ($pivotStatus === 'completed' && $assignment->assignable_type === 'examTraining') {
                try {
                    $performance = $this->assignmentService->getExamPerformance(
                        $studentId,
                        $assignment->assignable_id
                    );
                    $assignment->performance = $performance;
                } catch (\Exception $e) {
                    // If no answers found, set null performance
                    $assignment->performance = null;
                }
            }
        }

        // Transform assignments
        $transformedAssignments = $assignments->through(
            fn($assignment) =>
            new AssignmentResource($assignment)
        );

        return ApiResponse::paginatedWithData(
            $transformedAssignments,
            ['stats' => $stats],
            null,
            'Assignments retrieved successfully.'
        );
    }

    /**
     * Activate assignment (change status from not_started to in_progress)
     */
    public function activate(Request $request, int $id): JsonResponse
    {
        $studentId = Auth::user()->student->id;

        try {
            // Find assignment and verify it belongs to the student
            $assignment = $this->assignmentService->getAssignmentForStudent($id, $studentId);

            // Get current status
            $currentStatus = $assignment->students->first()?->pivot->status ?? 'not_started';

            if ($currentStatus !== 'not_started') {
                return ApiResponse::error(
                    'Assignment is already activated or completed.',
                    400
                );
            }

            // Activate the assignment
            $activatedAssignment = $this->assignmentService->activateAssignment($id, $studentId);

            // Transform the assignment
            $transformedAssignment = new AssignmentResource($activatedAssignment);

            return ApiResponse::success(
                $transformedAssignment,
                'Assignment activated successfully.'
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return ApiResponse::error(
                'Assignment not found.',
                404
            );
        }
    }
}
