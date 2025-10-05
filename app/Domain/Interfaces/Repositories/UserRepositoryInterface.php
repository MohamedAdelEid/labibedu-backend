<?php

namespace App\Domain\Interfaces\Repositories;

use App\Infrastructure\Models\User;

interface UserRepositoryInterface extends BaseRepositoryInterface
{
    public function findByUserName(string $userName): ?User;
    public function findByEmail(string $email): ?User;
}