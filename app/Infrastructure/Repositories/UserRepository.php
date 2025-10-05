<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Interfaces\Repositories\UserRepositoryInterface;
use App\Infrastructure\Models\User;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByUserName(string $userName): ?User
    {
        return $this->model->where('user_name', $userName)->first();
    }

    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }
}