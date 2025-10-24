<?php

namespace App\Presentation\Http\Middleware;

use App\Domain\Enums\UserRole;
use App\Infrastructure\Helpers\ApiResponse;
use Closure;
use Illuminate\Http\Request;

class AuthorizationMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = auth('api')->user();

        if (!$user) {
            return ApiResponse::error(__('auth.unauthenticated'), null, 401);
        }

        // If no roles specified, allow all authenticated users
        if (empty($roles)) {
            return $next($request);
        }

        // Check if user role is in allowed roles
        $userRole = $user->role->value;

        if (!in_array($userRole, $roles)) {
            return ApiResponse::error(
                __('auth.insufficient_permissions'),
                null,
                403
            );
        }

        return $next($request);
    }
}
