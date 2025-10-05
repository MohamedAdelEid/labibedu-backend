<?php

namespace App\Presentation\Http\Controllers\Api;

use App\Application\DTOs\Exam\SubmitAnswerDTO;
use App\Application\DTOs\Exam\SubmitEntireExamDTO;
use App\Application\Services\ExamService;
use App\Infrastructure\Helpers\ApiResponse;
use App\Presentation\Http\Requests\Exam\SubmitAnswerRequest;
use App\Presentation\Http\Requests\Exam\SubmitEntireExamRequest;
use App\Presentation\Http\Resources\ExamTrainingResource;
use App\Presentation\Http\Resources\ExamTrainingDetailResource;
use App\Presentation\Http\Resources\Question\QuestionResource;
use App\Presentation\Http\Resources\Question\TrainingQuestionResource;
use App\Presentation\Http\Resources\AnswerResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ExamController extends Controller
{
    public function __construct(
        private ExamService $examService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $studentId = auth()->user()->student->id;
        $type = $request->query('type');
        $perPage = $request->query('per_page', 15);

        $examsTrainings = $this->examService->getExamsAndTrainings($studentId, $type, $perPage);

        return ApiResponse::paginated(
            ExamTrainingResource::collection($examsTrainings),
            __('messages.retrieved_successfully')
        );
    }

    public function show(int $id, Request $request): JsonResponse
    {
        $perPage = $request->query('per_page', 10);
        
        $data = $this->examService->getDetails($id, $perPage);
        $examTraining = $data['examTraining'];
        $questions = $data['questions'];

        // <CHANGE> Determine which question resource to use based on type
        $questionResource = $examTraining->type->value === 'training' 
            ? TrainingQuestionResource::class 
            : QuestionResource::class;

        // <CHANGE> Get exam/training details without wrapper
        $examDetails = (new ExamTrainingDetailResource($examTraining))->resolve();

        // <CHANGE> Use ApiResponse::paginated for questions
        $response = ApiResponse::paginated(
            $questionResource::collection($questions),
            __('messages.retrieved_successfully')
        );

        // <CHANGE> Merge exam details with paginated questions
        $responseData = $response->getData(true);
        $responseData['data'] = array_merge($examDetails, ['questions' => $responseData['data']]);

        return response()->json($responseData, $response->status());
    }

    public function submitAnswer(SubmitAnswerRequest $request): JsonResponse
    {
        $studentId = auth()->user()->student->id;
        $dto = SubmitAnswerDTO::fromRequest($request->validated(), $studentId);

        $result = $this->examService->submitAnswer($dto);

        return ApiResponse::success([
            'answer' => new AnswerResource($result['answer']),
            'is_correct' => $result['is_correct'],
            'gained_xp' => $result['gained_xp'],
            'gained_coins' => $result['gained_coins'],
        ], __('messages.answer_submitted'));
    }

    public function submitEntireExam(SubmitEntireExamRequest $request): JsonResponse
    {
        $studentId = auth()->user()->student->id;
        $dto = SubmitEntireExamDTO::fromRequest($request->validated(), $studentId);

        $result = $this->examService->submitEntireExam($dto);

        return ApiResponse::success($result, __('messages.exam_submitted_successfully'));
    }
}