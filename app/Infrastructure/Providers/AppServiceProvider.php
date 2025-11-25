<?php

namespace App\Infrastructure\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Custom morph map for assignments
        Relation::enforceMorphMap([
            'examTraining' => 'App\Infrastructure\Models\ExamTraining',
            'video' => 'App\Infrastructure\Models\Video',
            'book' => 'App\Infrastructure\Models\Book',
        ]);
    }
}
