<?php

namespace App\Presentation\Http\Controllers\Api;

use App\Application\DTOs\Exam\SendHeartbeatDTO;
use App\Application\DTOs\Exam\SubmitAnswerDTO;
use App\Application\DTOs\Exam\SubmitEntireExamDTO;
use App\Presentation\Http\Resources\Answer\AnswerResource;
use App\Infrastructure\Facades\ExamFacade;
use App\Infrastructure\Helpers\ApiResponse;
use App\Presentation\Http\Requests\Exam\SubmitEntireExamRequest;
use App\Presentation\Http\Requests\Exam\SubmitAnswerRequest;
use App\Presentation\Http\Resources\Exam\ExamDetailsResource;
use App\Presentation\Http\Resources\Exam\ExamSummaryResource;
use App\Presentation\Http\Resources\Exam\ExamStatisticsResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class ExamController extends Controller
{
    public function __construct(
        private ExamFacade $examFacade
    ) {
    }

    public function show(int $id, Request $request): JsonResponse
    {
        $request->validate([
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $studentId = auth()->user()->student->id;
        $perPage = $request->input('per_page', 10);

        $data = $this->examFacade->getExamDetails($id, $studentId, 10);

        return ApiResponse::success(
            new ExamDetailsResource($data),
            'Exam details retrieved successfully.'
        );
    }

    public function start(int $id): JsonResponse
    {
        $studentId = auth()->user()->student->id;

        $result = $this->examFacade->startExam($id, $studentId);

        return ApiResponse::success($result, $result['message']);
    }

    public function submitAnswer(SubmitAnswerRequest $request): JsonResponse
    {
        $dto = new SubmitAnswerDTO(
            studentId: auth()->user()->student->id,
            questionId: $request->input('question_id'),
            selectedOptionIds: $request->input('selected_option_ids'),
            trueFalseAnswer: $request->input('true_false_answer'),
            connectPairs: $request->input('connect_pairs'),
            arrangeOptionIds: $request->input('arrange_option_ids'),
            writtenAnswer: $request->input('written_answer'),
            timeSpent: $request->input('time_spent', 0),
        );

        $result = $this->examFacade->submitAnswer($dto);

        return ApiResponse::success(
            new AnswerResource($result, $result['exam_training']),
            'Answer submitted successfully.'
        );
    }

    public function sendHeartbeat(int $id, Request $request): JsonResponse
    {
        $request->validate([
            'remaining_seconds' => 'required|integer|min:0',
        ]);

        $dto = new SendHeartbeatDTO(
            examId: $id,
            studentId: auth()->user()->student->id,
            remainingSeconds: $request->input('remaining_seconds'),
        );

        $result = $this->examFacade->sendHeartbeat($dto);

        return ApiResponse::success($result, 'Heartbeat sent successfully.');
    }

    public function submitEntireExam(int $id, SubmitEntireExamRequest $request): JsonResponse
    {
        $dto = new SubmitEntireExamDTO(
            studentId: auth()->user()->student->id,
            examTrainingId: $id,
            source: $request->input('source'),
            sourceId: $request->input('sourceId'),
        );

        $result = $this->examFacade->submitEntireExam($dto);

        return ApiResponse::success($result, 'Exam submitted successfully.');
    }

    public function statistics(int $id): JsonResponse
    {
        $studentId = auth()->user()->student->id;

        $data = $this->examFacade->getStatistics($id, $studentId);

        return ApiResponse::success(
            new ExamStatisticsResource($data),
            'Exam statistics retrieved successfully.'
        );
    }

    public function summary(int $id): JsonResponse
    {
        $studentId = auth()->user()->student->id;

        $data = $this->examFacade->getSummary($id, $studentId);

        return ApiResponse::success(
            new ExamSummaryResource($data),
            'Exam summary retrieved successfully.'
        );
    }
}
