<?php

namespace App\Presentation\Http\Resources\Lesson;

use App\Domain\Enums\AttemptStatus;
use App\Infrastructure\Models\ExamAttempt;
use App\Presentation\Http\Resources\Library\BookResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class LessonResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $trainingStatus = null;

        // Get training status if train_id exists
        if ($this->train_id) {
            $latestAttempt = $this->getLatestAttempt($this->train_id);

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
            'id' => $this->id,
            'title' => $this->title,
            'train_id' => $this->train_id,
            'training_status' => $trainingStatus,
            'category' => $this->whenLoaded('category', function () {
                return $this->category ? [
                    'id' => $this->category->id,
                    'name_ar' => $this->category->name_ar,
                    'name_en' => $this->category->name_en,
                ] : null;
            }),
            'books_count' => $this->books_count ?? 0,
            'videos_count' => $this->videos_count ?? 0,
            'books' => BookResource::collection($this->whenLoaded('books')),
            'videos' => $this->whenLoaded('videos', function () {
                return $this->videos->map(function ($video) {
                    return [
                        'id' => $video->id,
                        'title_ar' => $video->title_ar,
                        'title_en' => $video->title_en,
                        'url' => $video->url,
                        'cover' => $video->cover,
                        'subject' => $video->subject ? [
                            'id' => $video->subject->id,
                            'name' => $video->subject->name,
                        ] : null,
                    ];
                });
            }),
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

