<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class LocaleMiddleware
{
    /**
     * Supported locales for API.
     *
     * @var array<int, string>
     */
    private array $supportedLocales = ['vi', 'en'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->resolveLocale($request);
        App::setLocale($locale);

        $response = $next($request);
        $response->headers->set('Content-Language', $locale);

        return $response;
    }

    private function resolveLocale(Request $request): string
    {
        $header = $request->headers->get('Accept-Language');

        if (!is_string($header) || trim($header) === '') {
            return 'vi';
        }

        $primary = strtolower(trim(explode(',', $header)[0]));
        $primary = explode('-', $primary)[0];

        if (in_array($primary, $this->supportedLocales, true)) {
            return $primary;
        }

        return 'vi';
    }
}
