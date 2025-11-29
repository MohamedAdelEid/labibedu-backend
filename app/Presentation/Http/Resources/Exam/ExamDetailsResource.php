<?php

namespace App\Presentation\Http\Resources\Exam;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamDetailsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $examTraining = $this->resource['examTraining'];
        $questions = $this->resource['questions'];
        $attempt = $this->resource['attempt'] ?? null;
        $evaluatedQuestions = $this->resource['evaluatedQuestions'] ?? collect();

        // Get first related book if exists
        $book = $examTraining->book->first();

        // Get first related video if exists
        $video = $examTraining->video->first();

        return [
            'id' => $examTraining->id,
            'title' => $examTraining->title,
            'description' => $examTraining->description,
            'type' => $examTraining->type->value,
            'duration' => $examTraining->duration,
            'startDate' => $examTraining->start_date?->toIso8601String(),
            'endDate' => $examTraining->end_date?->toIso8601String(),
            'subject' => $examTraining->subject ? [
                'id' => $examTraining->subject->id,
                'name' => $examTraining->subject->name,
            ] : null,
            'book' => $book ? [
                'id' => $book->id,
                'title' => $book->title,
                'cover' => $book->cover,
                'thumbnail' => $book->thumbnail,
            ] : null,
            'video' => $video ? [
                'id' => $video->id,
                'title' => $video->title,
                'title_ar' => $video->title_ar,
                'title_en' => $video->title_en,
                'url' => $video->url,
                'cover' => $video->cover,
                'duration' => $video->duration,
            ] : null,
            'attempt' => $attempt ? new ExamAttemptResource($attempt) : null,
            'questions' => [
                'data' => $evaluatedQuestions->map(function ($evaluation) {
                    return new ExamQuestionResource($evaluation);
                })->toArray(),
                'pagination' => [
                    'currentPage' => $questions->currentPage(),
                    'totalPages' => $questions->lastPage(),
                    'pageSize' => $questions->perPage(),
                    'totalRecords' => $questions->total(),
                ],
            ],
            'createdAt' => $examTraining->created_at->toIso8601String(),
        ];
    }
}
