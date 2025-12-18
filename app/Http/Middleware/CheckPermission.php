<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    use ApiResponse;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (Auth::check() && Auth::user()->can($permission)) {
            return $next($request);
        }

        if (request()->ajax() || request()->wantsJson()) {
            return $this->setMessage(__('custom.auth.unauthorized'))->setCode(Response::HTTP_FORBIDDEN)->customResponse();
        } else {
            return to_route('dashboard.home')
                ->with(
                    'message',
                    [
                        'status' => false,
                        'content' => __('custom.auth.unauthorized'),
                    ]
                );
        }
    }
}
