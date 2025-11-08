<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Interfaces\Repositories\AgeGroupRepositoryInterface;
use App\Infrastructure\Models\AgeGroup;
use Illuminate\Support\Collection;

class AgeGroupRepository implements AgeGroupRepositoryInterface
{
    public function __construct(
        private AgeGroup $model
    ) {
    }

    /**
     * Get all age groups
     */
    public function all(): Collection
    {
        return $this->model->all();
    }
}

