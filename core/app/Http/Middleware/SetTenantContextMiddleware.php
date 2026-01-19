<?php

namespace App\Http\Middleware;

use App\Http\Responses\ApiResponse;
use App\Models\User;
use App\Services\TenantContextService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetTenantContextMiddleware
{
    public function __construct(
        private readonly TenantContextService $tenantContextService,
    ) {
    }

    /**
     * Attach actor context to the request.
     *
     * Sprint 0 note:
     * - Tenant isolation is enforced by resolving tenant from authenticated user.
     * - teacher_id MUST NOT come from client request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user instanceof User) {
            return ApiResponse::error('UNAUTHENTICATED', status: Response::HTTP_UNAUTHORIZED);
        }

        $request->attributes->set('actor_user_id', $user->id);
        $request->attributes->set('tenant_teacher_id', $this->tenantContextService->resolveTenant($user)['teacherId']);

        return $next($request);
    }
}
