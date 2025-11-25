<?php

namespace App\Presentation\Http\Resources\Assignment;

use App\Domain\Enums\AssignmentType;
use App\Presentation\Http\Resources\Assignment\BookAssignmentResource;
use App\Presentation\Http\Resources\Assignment\ExamTrainingAssignmentResource;
use App\Presentation\Http\Resources\Assignment\VideoAssignmentResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $type = $this->assignable_type;

        return match ($type) {
            AssignmentType::EXAM_TRAINING->value =>
            (new ExamTrainingAssignmentResource($this->resource))->toArray($request),
            AssignmentType::VIDEO->value =>
            (new VideoAssignmentResource($this->resource))->toArray($request),
            AssignmentType::BOOK->value =>
            (new BookAssignmentResource($this->resource))->toArray($request),
        };
    }
}
