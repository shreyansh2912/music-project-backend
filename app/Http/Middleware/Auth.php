<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Auth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! (in_array($request->get('user')->role, ['admin']))) {
            return response()->errorJson([], [
                'message' => 'The requested resource could not be found. Please contact support if the problem persists.'
            ], 404);
        }

        return $next($request);
    }
}
