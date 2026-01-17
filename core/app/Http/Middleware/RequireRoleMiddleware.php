<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireRoleMiddleware
{
    /**
     * @param  array<int, string>  $roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user instanceof User) {
            return ApiResponse::error('UNAUTHENTICATED', 'Unauthenticated.', Response::HTTP_UNAUTHORIZED);
        }

        $allowedRoles = array_values(array_filter($roles));

        if ($allowedRoles === []) {
            return ApiResponse::error('FORBIDDEN', 'Forbidden.', Response::HTTP_FORBIDDEN);
        }

        $roleValue = $user->role instanceof UserRole ? $user->role->value : (string) $user->role;

        if (!in_array($roleValue, $allowedRoles, true)) {
            return ApiResponse::error('FORBIDDEN', 'Forbidden.', Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
