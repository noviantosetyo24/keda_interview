<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleAuthenticate
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
        $url_segment = request()->segment(3);
        if (strtolower(auth()->user()->user_type) !== $url_segment) {
            return response()->json([
                'code' => 403,
                'message' => 'Access forbidden',
                'result' => []
            ], 403);
        }
        
        return $next($request);
    }
}
