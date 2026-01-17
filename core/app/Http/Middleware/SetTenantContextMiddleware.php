<?php

namespace App\Http\Middleware;

use App\Http\Responses\ApiResponse;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetTenantContextMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user instanceof User) {
            return ApiResponse::error('UNAUTHENTICATED', 'Unauthenticated.', Response::HTTP_UNAUTHORIZED);
        }

        $request->attributes->set('actor_user_id', $user->id);

        return $next($request);
    }
}
