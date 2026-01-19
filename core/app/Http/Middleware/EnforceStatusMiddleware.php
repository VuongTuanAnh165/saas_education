<?php

namespace App\Http\Middleware;

use App\Http\Responses\ApiResponse;
use App\Models\User;
use App\Services\TenantContextService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnforceStatusMiddleware
{
    public function __construct(
        private readonly TenantContextService $tenantContextService,
    ) {
    }

    /**
     * Enforce Sprint 0 access invariants for *every* protected request:
     * - user.status must be ACTIVE
     * - for TEACHER/STUDENT, teacher.status must be ACTIVE (chain lock)
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user instanceof User) {
            return ApiResponse::error('UNAUTHENTICATED', status: Response::HTTP_UNAUTHORIZED);
        }

        $this->tenantContextService->assertActorCanAccessSystem($user);

        return $next($request);
    }
}
