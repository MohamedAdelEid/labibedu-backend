<?php

namespace App\Presentation\Http\Middleware;

use App\Domain\Interfaces\Services\UserActivityServiceInterface;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserActivityMiddleware
{
    public function __construct(
        private UserActivityServiceInterface $userActivityService
    ) {
    }

    public function handle(Request $request, Closure $next)
    {
        // Only track activity for authenticated users
        if (Auth::check() && Auth::user()) {
            $user = Auth::user();
            $endpoint = $request->getPathInfo();
            $ipAddress = $request->ip();
            $userAgent = $request->userAgent();

            // Track API request activity
            $this->userActivityService->trackApiRequest(
                $user->id,
                $endpoint,
                $ipAddress,
                $userAgent
            );
        }

        return $next($request);
    }
}
