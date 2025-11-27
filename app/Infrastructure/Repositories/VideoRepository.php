<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Interfaces\Repositories\VideoRepositoryInterface;
use App\Infrastructure\Models\Video;
use App\Infrastructure\Models\VideoProgress;

class VideoRepository extends BaseRepository implements VideoRepositoryInterface
{
    public function __construct(Video $model)
    {
        parent::__construct($model);
    }

    public function findOrFail(int $id, array $columns = ['*']): Video
    {
        return $this->model->findOrFail($id, $columns);
    }

    public function findWithRelations(int $id): Video
    {
        return $this->model->with([
            'subject',
            'relatedTraining.questions.options'
        ])->findOrFail($id);
    }

    public function getProgress(int $studentId, int $videoId)
    {
        return VideoProgress::where('student_id', $studentId)
            ->where('video_id', $videoId)
            ->first();
    }

    public function updateProgress(int $studentId, int $videoId, array $data)
    {
        return VideoProgress::updateOrCreate(
            [
                'student_id' => $studentId,
                'video_id' => $videoId,
            ],
            $data
        );
    }

    public function getByRelatedTrainingId(int $trainingId): \Illuminate\Support\Collection
    {
        return $this->model->where('related_training_id', $trainingId)->get();
    }
}
