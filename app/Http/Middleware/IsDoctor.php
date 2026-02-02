<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsDoctor
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user('doctor')) {  // use doctor guard
            return response()->json([
                'message' => 'Unauthorized — Not logged in as doctor'
            ], 401);
        }

        return $next($request);
    }
}
