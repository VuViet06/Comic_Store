<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || $user->role !== \App\Models\User::ROLE_USER) {
            abort(403, 'Chỉ người dùng thông thường mới truy cập được khu vực này.');
        }

        return $next($request);
    }
}


