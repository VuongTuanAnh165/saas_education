<?php

namespace App\Http\Middleware;

use App\Http\Responses\ApiResponse;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnforceStatusMiddleware
{
    /**
     * Sprint 0 placeholder middleware.
     *
     * Status checks (user/teacher chain lock) are enforced in service layer
     * via TenantContextService::assertActorCanAccessSystem.
     *
     * We keep this middleware as a no-op gate to:
     * - ensure request is authenticated
     * - keep route groups explicit and future-proof
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user instanceof User) {
            return ApiResponse::error('UNAUTHENTICATED', status: Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
