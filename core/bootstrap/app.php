<?php

use App\Exceptions\BusinessException;
use App\Exceptions\SystemException;
use App\Http\Middleware\EnforceStatusMiddleware;
use App\Http\Middleware\LocaleMiddleware;
use App\Http\Middleware\RequestIdMiddleware;
use App\Http\Middleware\RequireRoleMiddleware;
use App\Http\Middleware\SetTenantContextMiddleware;
use App\Http\Responses\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(RequestIdMiddleware::class);
        $middleware->append(LocaleMiddleware::class);

        $middleware->alias([
            'require.role' => RequireRoleMiddleware::class,
            'tenant.context' => SetTenantContextMiddleware::class,
            'enforce.status' => EnforceStatusMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (ValidationException $e) {
            return ApiResponse::error(
                code: 'VALIDATION_ERROR',
                status: Response::HTTP_UNPROCESSABLE_ENTITY,
                data: ['errors' => $e->errors()],
            );
        });

        $exceptions->render(function (AuthenticationException $e) {
            return ApiResponse::error(
                code: 'UNAUTHENTICATED',
                status: Response::HTTP_UNAUTHORIZED,
            );
        });

        $exceptions->render(function (BusinessException $e) {
            return ApiResponse::error(
                code: $e->errorCode,
                message: $e->getMessage() !== '' ? $e->getMessage() : null,
                status: $e->status,
            );
        });

        $exceptions->render(function (SystemException $e) {
            return ApiResponse::error(
                code: $e->errorCode,
                message: null,
                status: $e->status,
            );
        });

        $exceptions->render(function (ModelNotFoundException $e) {
            return ApiResponse::error(
                code: 'NOT_FOUND',
                status: Response::HTTP_NOT_FOUND,
            );
        });

        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpExceptionInterface $e) {
            $status = $e->getStatusCode();

            if ($status === Response::HTTP_FORBIDDEN) {
                return ApiResponse::error('FORBIDDEN', status: Response::HTTP_FORBIDDEN);
            }

            if ($status === Response::HTTP_NOT_FOUND) {
                return ApiResponse::error('NOT_FOUND', status: Response::HTTP_NOT_FOUND);
            }

            if ($status === Response::HTTP_TOO_MANY_REQUESTS) {
                return ApiResponse::error('RATE_LIMITED', status: Response::HTTP_TOO_MANY_REQUESTS);
            }

            return ApiResponse::error('HTTP_ERROR', message: $e->getMessage(), status: $status);
        });

        $exceptions->render(function (\Throwable $e) {
            return ApiResponse::error(
                code: 'INTERNAL_ERROR',
                message: null,
                status: Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        });
    })->create();
