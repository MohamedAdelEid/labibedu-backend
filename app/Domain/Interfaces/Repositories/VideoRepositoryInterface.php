<?php

namespace App\Domain\Interfaces\Repositories;

use App\Infrastructure\Models\Video;

interface VideoRepositoryInterface
{
    public function findOrFail(int $id, array $columns = ['*']): Video;

    public function getProgress(int $studentId, int $videoId);

    public function updateProgress(int $studentId, int $videoId, array $data);

    public function getByRelatedTrainingId(int $trainingId): \Illuminate\Support\Collection;
}
