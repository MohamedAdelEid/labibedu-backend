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
use App\Domain\Interfaces\Repositories\UserActivityRepositoryInterface;
use App\Infrastructure\Repositories\ExamAttemptRepository;
use App\Infrastructure\Repositories\QuestionRepository;
use App\Infrastructure\Repositories\VideoRepository;
use App\Infrastructure\Repositories\BookRepository;
use App\Infrastructure\Repositories\AssignmentRepository;
use App\Infrastructure\Repositories\UserActivityRepository;
use App\Domain\Interfaces\Repositories\StudentRepositoryInterface;
use App\Infrastructure\Repositories\StudentRepository;
use App\Domain\Interfaces\Services\UserActivityServiceInterface;
use App\Application\Services\UserActivityService;
use App\Domain\Interfaces\Services\StudentServiceInterface;
use App\Application\Services\StudentService;
use App\Domain\Interfaces\Services\BookServiceInterface;
use App\Application\Services\BookService;
use App\Domain\Interfaces\Services\VideoServiceInterface;
use App\Application\Services\VideoService;
use App\Domain\Interfaces\Services\AvatarServiceInterface;
use App\Application\Services\AvatarService;
use App\Domain\Interfaces\Services\QuestionServiceInterface;
use App\Application\Services\QuestionService;
use App\Domain\Interfaces\Repositories\AvatarRepositoryInterface;
use App\Infrastructure\Repositories\AvatarRepository;
use App\Infrastructure\Facades\ExamFacade;
use App\Domain\Interfaces\Repositories\AvatarCategoryRepositoryInterface;
use App\Infrastructure\Repositories\AvatarCategoryRepository;
use App\Domain\Interfaces\Services\AvatarCategoryServiceInterface;
use App\Application\Services\AvatarCategoryService;
use App\Domain\Interfaces\Repositories\StudentBookRepositoryInterface;
use App\Infrastructure\Repositories\StudentBookRepository;
use App\Domain\Interfaces\Repositories\LevelRepositoryInterface;
use App\Infrastructure\Repositories\LevelRepository;
use App\Domain\Interfaces\Repositories\PageRepositoryInterface;
use App\Infrastructure\Repositories\PageRepository;
use App\Infrastructure\Facades\LibraryFacade;
use App\Application\Services\LibraryService;
use App\Application\Services\BookProgressService;
use App\Application\Services\StudentBookService;
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
        $this->app->bind(UserActivityRepositoryInterface::class, UserActivityRepository::class);
        $this->app->bind(StudentRepositoryInterface::class, StudentRepository::class);
        $this->app->bind(AvatarRepositoryInterface::class, AvatarRepository::class);
        $this->app->bind(AvatarCategoryRepositoryInterface::class, AvatarCategoryRepository::class);
        $this->app->bind(StudentBookRepositoryInterface::class, StudentBookRepository::class);
        $this->app->bind(LevelRepositoryInterface::class, LevelRepository::class);
        $this->app->bind(PageRepositoryInterface::class, PageRepository::class);

        // Bind services
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(UserActivityServiceInterface::class, UserActivityService::class);
        $this->app->bind(StudentServiceInterface::class, StudentService::class);
        $this->app->bind(BookServiceInterface::class, BookService::class);
        $this->app->bind(VideoServiceInterface::class, VideoService::class);
        $this->app->bind(AvatarServiceInterface::class, AvatarService::class);
        $this->app->bind(AvatarCategoryServiceInterface::class, AvatarCategoryService::class);
        $this->app->bind(QuestionServiceInterface::class, QuestionService::class);
        $this->app->singleton(CookieService::class);
        $this->app->singleton(ExamService::class);
        $this->app->singleton(LibraryService::class);
        $this->app->singleton(BookProgressService::class);
        $this->app->singleton(StudentBookService::class);

        // Bind facades
        $this->app->singleton(AuthFacade::class, function ($app) {
            return new AuthFacade($app->make(AuthServiceInterface::class));
        });
        $this->app->singleton(ExamFacade::class, function ($app) {
            return new ExamFacade($app->make(ExamService::class));
        });
        $this->app->singleton(LibraryFacade::class, function ($app) {
            return new LibraryFacade(
                $app->make(LibraryService::class),
                $app->make(StudentBookService::class)
            );
        });
    }

    public function boot(): void
    {
        //
    }
}