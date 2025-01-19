<?php

namespace App\Http\Middleware;

use App\Http\Controllers\UserController;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class AuthSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $validator = Validator::make([
            'x-session-token' => $request->header('x-session-token')
        ], [
            'x-session-token' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->errorJson([], [
                'message' => 'Please login and try again.'
            ], 400);
        }

        $session = (new UserController)->getUserBySession($request->header('x-session-token'));
        if (!$session) {
            return response()->errorJson([], [
                'message' => 'Invalid session. Please login and try again.'
            ], 401);
        }

        $request->attributes->add([
            'user' => $session->user,
        ]);

        return $next($request);
    }
}
