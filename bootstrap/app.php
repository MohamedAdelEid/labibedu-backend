<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'jwt.auth' => \App\Presentation\Http\Middleware\JwtMiddleware::class,
            'localization' => \App\Presentation\Http\Middleware\LocalizationMiddleware::class,
        ]);

        $middleware->web(append: [
            \App\Presentation\Http\Middleware\LocalizationMiddleware::class,
        ]);

        $middleware->api(append: [
            \App\Presentation\Http\Middleware\LocalizationMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $e, $request) {
            if ($request->is('api/*')) {
                return app(\App\Application\Exceptions\Handler::class)->handleApiException($request, $e);
            }
        });
    })->create();
