<?php

namespace App\Infrastructure\Providers;

use App\Application\Services\AuthService;
use App\Domain\Interfaces\Repositories\UserRepositoryInterface;
use App\Domain\Interfaces\Services\AuthServiceInterface;
use App\Infrastructure\Facades\AuthFacade;
use App\Infrastructure\Repositories\UserRepository;
use App\Infrastructure\Repositories\ExamTrainingRepository;
use App\Infrastructure\Repositories\AnswerRepository;
use App\Domain\Interfaces\Repositories\ExamTrainingRepositoryInterface;
use App\Domain\Interfaces\Repositories\AnswerRepositoryInterface;
use App\Application\Services\ExamService;
use App\Application\Services\CookieService;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind repositories
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(ExamTrainingRepositoryInterface::class, ExamTrainingRepository::class);
        $this->app->bind(AnswerRepositoryInterface::class, AnswerRepository::class);

        // Bind services
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->singleton(CookieService::class);
        $this->app->singleton(ExamService::class);

        // Bind facades
        $this->app->singleton(AuthFacade::class, function ($app) {
            return new AuthFacade($app->make(AuthServiceInterface::class));
        });
    }

    public function boot(): void
    {
        //
    }
}