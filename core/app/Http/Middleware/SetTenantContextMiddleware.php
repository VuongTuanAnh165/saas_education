<?php

namespace App\Http\Middleware;

use App\Http\Responses\ApiResponse;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetTenantContextMiddleware
{
    /**
     * Attach actor context to the request.
     *
     * Sprint 0 note:
     * - Tenant isolation is enforced in service layer (TenantContextService).
     * - Middleware MUST stay thin and MUST NOT query tenant data.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user instanceof User) {
            return ApiResponse::error('UNAUTHENTICATED', status: Response::HTTP_UNAUTHORIZED);
        }

        $request->attributes->set('actor_user_id', $user->id);

        return $next($request);
    }
}
