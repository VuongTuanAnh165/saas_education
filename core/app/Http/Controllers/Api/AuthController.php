<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\LogoutRequest;
use App\Http\Resources\AuthLoginDataResource;
use App\Http\Resources\EmptyDataResource;
use App\Http\Responses\ApiResponse;
use App\Models\User;
use App\Services\AuthService;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $authService,
    ) {
    }

    public function login(LoginRequest $request)
    {
        $result = $this->authService->login(
            email: (string) $request->validated('email'),
            password: (string) $request->validated('password'),
        );

        return ApiResponse::success(
            data: AuthLoginDataResource::make($result),
            code: 'AUTH_LOGIN_SUCCESS',
            status: Response::HTTP_OK,
        );
    }

    public function logout(LogoutRequest $request)
    {
        /** @var User $actor */
        $actor = $request->user();

        $this->authService->ensureActive($actor);
        $this->authService->logout($actor);

        return ApiResponse::success(
            data: EmptyDataResource::make(null),
            code: 'AUTH_LOGOUT_SUCCESS',
            status: Response::HTTP_OK,
        );
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $this->authService->forgotPassword((string) $request->validated('email'));

        return ApiResponse::success(
            data: EmptyDataResource::make(null),
            code: 'AUTH_FORGOT_PASSWORD_ACCEPTED',
            status: Response::HTTP_OK,
        );
    }
}
