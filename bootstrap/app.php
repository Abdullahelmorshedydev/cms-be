<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            Route::prefix(LaravelLocalization::setLocale())
                ->middleware(['web', 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath'])
                ->group(base_path('routes/web.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->group(
            'web',
            [
                \Illuminate\Cookie\Middleware\EncryptCookies::class,
                \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
                \Illuminate\Session\Middleware\StartSession::class,
                \Illuminate\View\Middleware\ShareErrorsFromSession::class,
                \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
                \Illuminate\Routing\Middleware\SubstituteBindings::class,
                \Illuminate\Session\Middleware\AuthenticateSession::class
            ]
        );

        $middleware->alias([
            'check.permission' => \App\Http\Middleware\CheckPermission::class,
            'abilities' => \Laravel\Sanctum\Http\Middleware\CheckAbilities::class,
            'ability' => \Laravel\Sanctum\Http\Middleware\CheckForAnyAbility::class,
            'can' => \Illuminate\Auth\Middleware\Authorize::class,
            'APILocaleMiddleware' => \App\Http\Middleware\APILocaleMiddleware::class,
            'localize' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRoutes::class,
            'localizationRedirect' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationRedirectFilter::class,
            'localeSessionRedirect' => \Mcamara\LaravelLocalization\Middleware\LocaleSessionRedirect::class,
            'localeCookieRedirect' => \Mcamara\LaravelLocalization\Middleware\LocaleCookieRedirect::class,
            'localeViewPath' => \Mcamara\LaravelLocalization\Middleware\LaravelLocalizationViewPath::class,

            // Authentication middleware
            'auth' => \App\Http\Middleware\Authenticate::class,

            // Guest middleware
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,

            'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
            'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
            'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Validation Exception
        $exceptions->render(function (ValidationException $validationException, Request $request) {
            $message = __('custom.exceptions.validation_error');
            $errors = $validationException->errors();

            // Translate validation errors
            $translatedErrors = [];
            foreach ($errors as $key => $errorMessages) {
                $translatedErrors[$key] = array_map(function ($msg) {
                    // Check if message is already a translation key
                    if (str_starts_with($msg, 'custom.')) {
                        return __($msg);
                    }
                    return $msg;
                }, $errorMessages);
            }

            if ($request->ajax() || $request->expectsJson()) {
                return apiResponse(
                    code: Response::HTTP_UNPROCESSABLE_ENTITY,
                    errors: $translatedErrors,
                    message: $message
                );
            } else {
                return back()->with('message', [
                    'status' => false,
                    'content' => $message
                ])->withErrors($translatedErrors)->withInput();
            }
        });

        // Access Denied (403)
        $exceptions->render(function (AccessDeniedHttpException $e, Request $request) {
            $message = $e->getMessage() ?: __('custom.exceptions.forbidden');
            if ($request->ajax() || $request->expectsJson()) {
                return apiResponse(
                    code: Response::HTTP_FORBIDDEN,
                    message: $message
                );
            } else {
                // Check if it's a site route or dashboard route
                if ($request->is('*dashboard*')) {
                    return response()->view('admin.errors.403', ['message' => $message], 403);
                }
                return response()->view('site.errors.403', ['message' => $message], 403);
            }
        });

        // Authentication Exception (401)
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            $message = __('custom.exceptions.unauthorized');
            if ($request->ajax() || $request->expectsJson()) {
                return apiResponse(
                    code: Response::HTTP_UNAUTHORIZED,
                    message: $message
                );
            } else {
                // Check if it's a site route or dashboard route
                if ($request->is('*dashboard*')) {
                    return to_route('dashboard.login')->with('message', [
                        'content' => $message,
                        'status' => false,
                    ]);
                }
                return redirect()->route('site.login')->with('message', [
                    'content' => $message,
                    'status' => false,
                ]);
            }
        });

        // Model Not Found (404)
        $exceptions->render(function (ModelNotFoundException $e, Request $request) {
            $message = __('custom.exceptions.model_not_found');
            if ($request->ajax() || $request->expectsJson()) {
                return apiResponse(
                    code: Response::HTTP_NOT_FOUND,
                    message: $message
                );
            } else {
                if ($request->is('*dashboard*')) {
                    return response()->view('admin.errors.404', ['message' => $message], 404);
                }
                return response()->view('site.errors.404', ['message' => $message], 404);
            }
        });

        // Not Found HTTP Exception (404)
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            // Log detailed information about 404 errors for debugging
            \Log::warning('404 Not Found', [
                'method' => $request->method(),
                'url' => $request->fullUrl(),
                'path' => $request->path(),
                'route' => $request->route()?->getName(),
                'locale' => app()->getLocale(),
            ]);

            $message = __('custom.exceptions.not_found');
            if ($request->ajax() || $request->expectsJson()) {
                return apiResponse(
                    code: Response::HTTP_NOT_FOUND,
                    message: $message
                );
            } else {
                if ($request->is('*dashboard*')) {
                    return response()->view('admin.errors.404', ['message' => $message], 404);
                }
                return response()->view('site.errors.404', ['message' => $message], 404);
            }
        });

        // Throttle/Too Many Requests (429)
        $exceptions->render(function (ThrottleRequestsException $e, Request $request) {
            $retryAfter = $e->getHeaders()['Retry-After'] ?? 60;
            $message = __('custom.exceptions.throttle_message', ['seconds' => $retryAfter]);

            if ($request->ajax() || $request->expectsJson()) {
                return apiResponse(
                    code: Response::HTTP_TOO_MANY_REQUESTS,
                    message: $message
                )->withHeaders([
                            'Retry-After' => $retryAfter
                        ]);
            } else {
                if ($request->is('*dashboard*')) {
                    return response()->view('admin.errors.429', ['message' => $message], 429);
                }
                return response()->view('site.errors.429', ['message' => $message], 429);
            }
        });

        // Method Not Allowed (405)
        $exceptions->render(function (MethodNotAllowedHttpException $e, Request $request) {
            $message = __('custom.exceptions.method_not_allowed');
            if ($request->ajax() || $request->expectsJson()) {
                return apiResponse(
                    code: Response::HTTP_METHOD_NOT_ALLOWED,
                    message: $message
                );
            } else {
                if ($request->is('*dashboard*')) {
                    return response()->view('admin.errors.405', ['message' => $message], 405);
                }
                return response()->view('site.errors.405', ['message' => $message], 405);
            }
        });

        // General Exception Handler (500)
        $exceptions->render(function (\Exception $e, Request $request) {
            dd($e->getMessage(), $e);
            // Log the exception
            \Log::error('Exception occurred', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // Don't expose internal errors in production
            $message = app()->environment('production')
                ? __('custom.exceptions.internal_server_error')
                : $e->getMessage();

            if ($request->ajax() || $request->expectsJson()) {
                return apiResponse(
                    code: Response::HTTP_INTERNAL_SERVER_ERROR,
                    message: $message
                );
            } else {
                if ($request->is('*dashboard*')) {
                    return response()->view('admin.errors.500', ['message' => $message], 500);
                }
                return response()->view('site.errors.500', ['message' => $message], 500);
            }
        });
    })->create();
