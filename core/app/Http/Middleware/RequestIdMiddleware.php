<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequestIdMiddleware
{
    public const HEADER_NAME = 'X-Request-Id';
    public const ATTRIBUTE_NAME = 'request_id';

    public function handle(Request $request, Closure $next): Response
    {
        $requestId = $request->headers->get(self::HEADER_NAME);

        if (!is_string($requestId) || trim($requestId) === '') {
            $requestId = (string) str()->uuid();
        }

        $request->attributes->set(self::ATTRIBUTE_NAME, $requestId);

        $response = $next($request);
        $response->headers->set(self::HEADER_NAME, $requestId);

        return $response;
    }
}
