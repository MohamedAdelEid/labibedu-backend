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
use App\Domain\Interfaces\Repositories\ExamAttemptRepositoryInterface;
use App\Domain\Interfaces\Repositories\QuestionRepositoryInterface;
use App\Domain\Interfaces\Repositories\VideoRepositoryInterface;
use App\Domain\Interfaces\Repositories\BookRepositoryInterface;
use App\Domain\Interfaces\Repositories\AssignmentRepositoryInterface;
use App\Infrastructure\Repositories\ExamAttemptRepository;
use App\Infrastructure\Repositories\QuestionRepository;
use App\Infrastructure\Repositories\VideoRepository;
use App\Infrastructure\Repositories\BookRepository;
use App\Infrastructure\Repositories\AssignmentRepository;
use App\Infrastructure\Facades\ExamFacade;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind repositories
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(ExamTrainingRepositoryInterface::class, ExamTrainingRepository::class);
        $this->app->bind(AnswerRepositoryInterface::class, AnswerRepository::class);
        $this->app->bind(ExamAttemptRepositoryInterface::class, ExamAttemptRepository::class);
        $this->app->bind(QuestionRepositoryInterface::class, QuestionRepository::class);
        $this->app->bind(VideoRepositoryInterface::class, VideoRepository::class);
        $this->app->bind(BookRepositoryInterface::class, BookRepository::class);
        $this->app->bind(AssignmentRepositoryInterface::class, AssignmentRepository::class);


        // Bind services
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->singleton(CookieService::class);
        $this->app->singleton(ExamService::class);

        // Bind facades
        $this->app->singleton(AuthFacade::class, function ($app) {
            return new AuthFacade($app->make(AuthServiceInterface::class));
        });
        $this->app->singleton(ExamFacade::class, function ($app) {
            return new ExamFacade($app->make(ExamService::class));
        });

    }

    public function boot(): void
    {
        //
    }
}