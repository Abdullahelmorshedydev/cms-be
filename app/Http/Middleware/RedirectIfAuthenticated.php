<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * Default guest middleware - checks all guards and redirects if authenticated.
     * For specific guards, use RedirectIfDashboardAuthenticated or RedirectIfWebsiteAuthenticated.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        // If no guards specified, check web guard (default)
        if (empty($guards)) {
            $guards = ['web'];
        }

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Already authenticated'], 200);
                }

                // If accessing dashboard login and user is admin, redirect to dashboard home
                if ($request->is('dashboard/*') || $request->is('*/dashboard/*')) {
                    if ($user && $user->isAdmin()) {
                        return redirect()->route('dashboard.home');
                    }
                }

                // If accessing site login and user is not admin, redirect to site home
                if ($request->is('login') || $request->is('site/*')) {
                    if ($user && !$user->isAdmin()) {
                        return redirect()->route('site.home');
                    } elseif ($user && $user->isAdmin()) {
                        return redirect()->route('dashboard.home');
                    }
                }

                // Default redirect based on user type
                if ($user) {
                    if ($user->isAdmin()) {
                        return redirect()->route('dashboard.home');
                    } else {
                        return redirect()->route('site.home');
                    }
                }
            }
        }

        return $next($request);
    }
}
