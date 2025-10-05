<?php

namespace App\Application\Exceptions;

use App\Application\Exceptions\InvalidCredentialsException;
use App\Application\Exceptions\InvalidTokenException;
use App\Infrastructure\Helpers\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use InvalidArgumentException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        // API requests should return JSON
        if ($request->is('api/*')) {
            return $this->handleApiException($request, $e);
        }

        return parent::render($request, $e);
    }

    public function handleApiException($request, Throwable $e)
    {
        // Validation errors
        if ($e instanceof ValidationException) {
            return ApiResponse::error(
                __('validation.attributes.validation_failed'),
                $e->errors(),
                422
            );
        }

        // Authentication errors
        if ($e instanceof AuthenticationException) {
            return ApiResponse::error(
                __('auth.unauthenticated'),
                null,
                401
            );
        }

        // JWT Unauthorized errors
        if ($e instanceof UnauthorizedHttpException) {
            return ApiResponse::error(
                $e->getMessage() ?: __('auth.token_not_provided'),
                null,
                401
            );
        }

        // Custom application exceptions
        if ($e instanceof InvalidCredentialsException || $e instanceof InvalidTokenException) {
            return ApiResponse::error(
                $e->getMessage(),
                null,
                $e->getCode() ?: 401
            );
        }

        // Model not found
        if ($e instanceof ModelNotFoundException) {
            return ApiResponse::error(
                __('messages.resource_not_found'),
                null,
                404
            );
        }

        // Route not found
        if ($e instanceof NotFoundHttpException) {
            return ApiResponse::error(
                __('messages.route_not_found'),
                null,
                404
            );
        }

        // Method not allowed
        if ($e instanceof MethodNotAllowedHttpException) {
            return ApiResponse::error(
                __('messages.method_not_allowed'),
                null,
                405
            );
        }

        // Invalid argument (like missing view files)
        if ($e instanceof InvalidArgumentException) {
            return ApiResponse::error(
                $e->getMessage(),
                null,
                500
            );
        }

        // Generic server error
        if (config('app.debug')) {
            return ApiResponse::error(
                $e->getMessage(),
                [
                    'exception' => get_class($e),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                ],
                500
            );
        }

        return ApiResponse::error(
            __('messages.server_error'),
            null,
            500
        );
    }
}