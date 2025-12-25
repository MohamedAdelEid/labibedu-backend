<?php

namespace App\Presentation\Http\Resources\Lesson;

use App\Domain\Enums\AttemptStatus;
use App\Infrastructure\Models\ExamAttempt;
use App\Presentation\Http\Resources\Library\BookResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LessonResource extends JsonResource
{
    /**
     * Static cache for attempts to avoid N+1 queries within the same request
     * Keyed by exam_training_id
     */
    private static ?array $attemptsCache = null;

    public function toArray(Request $request): array
    {
        $trainingStatus = null;

        // Get training status if train_id exists
        if ($this->train_id) {
            $latestAttempt = $this->getLatestAttempt($this->train_id);

            if ($latestAttempt) {
                // Use the model's helper methods to check status
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
     * Get latest attempt for training from cache
     */
    private function getLatestAttempt(int $trainingId): ?ExamAttempt
    {
        if (self::$attemptsCache === null) {
            return null;
        }

        return self::$attemptsCache[$trainingId] ?? null;
    }

    /**
     * Set attempts cache (called from controller to batch load attempts)
     */
    public static function setAttemptsCache(array $attempts): void
    {
        self::$attemptsCache = [];
        foreach ($attempts as $key => $attempt) {
            if ($attempt instanceof ExamAttempt) {
                // Use exam_training_id as key
                $trainingId = $attempt->exam_training_id;
                self::$attemptsCache[$trainingId] = $attempt;
            }
        }
    }

    /**
     * Reset cache (useful for testing or when processing multiple collections)
     */
    public static function resetCache(): void
    {
        self::$attemptsCache = null;
    }
}

