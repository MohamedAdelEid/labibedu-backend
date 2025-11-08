<?php

namespace App\Domain\Interfaces\Repositories;

use Illuminate\Support\Collection;

interface AgeGroupRepositoryInterface
{
    /**
     * Get all age groups
     *
     * @return Collection
     */
    public function all(): Collection;
}

