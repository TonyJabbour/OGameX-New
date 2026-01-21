<?php

namespace OGame\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckBanned
{
    /**
     * Routes that should skip the banned check.
     *
     * @var array
     */
    protected $except = [
        'banned',
        'logout',
        'login',
        'register',
        'password.*',
        'api.*',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip check if not authenticated or on excluded routes
        if (!Auth::check() || $this->shouldSkip($request)) {
            return $next($request);
        }
        
        $user = Auth::user();
        
        // Only perform ban check if user has is_banned flag set
        // This avoids calling isBanned() for every user on every request
        if ($user->is_banned && $user->isBanned()) {
            return redirect()->route('banned');
        }
        
        return $next($request);
    }

    /**
     * Determine if the request should skip the banned check.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function shouldSkip(Request $request): bool
    {
        foreach ($this->except as $except) {
            if ($request->routeIs($except)) {
                return true;
            }
        }

        return false;
    }
}
