<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

/**
 * Default Authenticate Middleware
 *
 * This is the default authentication middleware that uses the 'web' guard.
 * For specific guards, use AuthenticateDashboard or AuthenticateWebsite middleware.
 *
 * @package App\Http\Middleware
 */
class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * This is the default Authenticate middleware - it redirects to dashboard login.
     * For specific guards, use AuthenticateDashboard or AuthenticateWebsite middleware.
     *
     * @param Request $request
     * @return string|null
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson() || $request->is('api/*'))
            return null;

        // Check if request is for dashboard routes
        if ($request->is('dashboard/*') || $request->is('*/dashboard/*')) {
            return route('dashboard.login');
        }

        // Default to site login for other routes
        return route('site.login');
    }
}
