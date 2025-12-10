<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class APILocaleMiddleware
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
    if ($request->hasHeader("Accept-Language")) {
      $supportedLocales = LaravelLocalization::getSupportedLanguagesKeys();
      $locale = $request->header("Accept-Language");
      if (in_array($locale, $supportedLocales)) {
        app()->setLocale($locale);
      }
    }

    if ($request->has('locale')) {
      app()->setLocale($request->get('locale'));
    }

    return $next($request);
  }
}
