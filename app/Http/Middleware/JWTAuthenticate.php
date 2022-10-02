<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use JWTAuth;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class JWTAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException) {
                return response()->json([
                    'code' => 401,
                    'message' => 'Token is invalid',
                    'result' => []
                ], 401);
            } else if ($e instanceof TokenExpiredException) {
                return response()->json([
                    'code' => 401,
                    'message' => 'Token is expired',
                    'result' => []
                ], 401);
            } else {
                return response()->json([
                    'code' => 401,
                    'message' => 'Authorization Token not found',
                    'result' => []
                ], 401);
            }
        }
        return $next($request);
    }
}
