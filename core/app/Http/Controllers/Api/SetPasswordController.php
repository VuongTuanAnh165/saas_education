<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SetPasswordRequest;
use App\Http\Resources\EmptyDataResource;
use App\Http\Responses\ApiResponse;
use App\Services\AuthService;
use Symfony\Component\HttpFoundation\Response;

class SetPasswordController extends Controller
{
    public function __construct(
        private readonly AuthService $authService,
    ) {
    }

    public function __invoke(SetPasswordRequest $request)
    {
        $this->authService->setPasswordBySetupToken(
            token: (string) $request->validated('token'),
            password: (string) $request->validated('password'),
        );

        return ApiResponse::success(
            data: EmptyDataResource::make(null),
            code: 'AUTH_PASSWORD_SET_SUCCESS',
            status: Response::HTTP_OK,
        );
    }
}
