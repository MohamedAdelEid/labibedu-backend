<?php

namespace App\Presentation\Http\Resources\Library;

use App\Infrastructure\Models\ExamAttempt;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class BookPagesResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $trainingStatus = null;
        $trainingId = $this->resource['book']?->related_training_id;

        // Get training status if training_id exists
        if ($trainingId) {
            $latestAttempt = $this->getLatestAttempt($trainingId);

            if ($latestAttempt) {
                // Convert enum status to string status
                if ($latestAttempt->isFinished()) {
                    $trainingStatus = 'completed';
                } elseif ($latestAttempt->isInProgress()) {
                    $trainingStatus = 'in_progress';
                }
            } else {
                $trainingStatus = 'not_started';
            }
        }

        return [
            'book_id' => $this->resource['book']->id,
            'language' => $this->resource['book']?->language ?? null,
            'total_pages' => $this->resource['pages']->count() ?? 0,
            'last_read_page_id' => $this->resource['last_read_page_id'],
            'examTrainingId' => $trainingId,
            'training_status' => $trainingStatus,
            'cover' => $this->resource['book']?->cover,
            'pages' => PageResource::collection($this->resource['pages']),
        ];
    }

    /**
     * Get latest attempt for training
     */
    private function getLatestAttempt(int $trainingId): ?ExamAttempt
    {
        $user = Auth::user();

        if (!$user || !$user->student) {
            return null;
        }

        $studentId = $user->student->id;

        return ExamAttempt::where('student_id', $studentId)
            ->where('exam_training_id', $trainingId)
            ->latest()
            ->first();
    }
}