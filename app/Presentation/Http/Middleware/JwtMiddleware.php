<?php

namespace App\Presentation\Http\Middleware;

use App\Infrastructure\Helpers\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return ApiResponse::error(__('auth.user_not_found'), null, 401);
            }

        } catch (TokenExpiredException $e) {
            return ApiResponse::error(__('auth.token_expired'), null, 401);
        } catch (TokenInvalidException $e) {
            return ApiResponse::error(__('auth.token_invalid'), null, 401);
        } catch (JWTException $e) {
            return ApiResponse::error(__('auth.token_absent'), null, 401);
        }

        return $next($request);
    }
}