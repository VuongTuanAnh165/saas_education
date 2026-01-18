<?php

namespace App\Http\Responses;

use App\Http\Resources\ApiResponseResource;
use Symfony\Component\HttpFoundation\Response;

class ApiResponse
{
    /**
     * Translate API code to human-readable message.
     *
     * Convention:
     * - API code is SCREAMING_SNAKE_CASE (e.g. AUTH_LOGIN_SUCCESS).
     * - Lang key is snake_case (e.g. auth_login_success).
     */
    private static function translate(string $code): ?string
    {
        $key = strtolower($code);
        $key = str_replace('-', '_', $key);
        $key = preg_replace('/[^a-z0-9_]+/', '_', $key) ?? $key;
        $key = trim($key, '_');

        $translated = trans('api.' . $key);

        return $translated !== 'api.' . $key ? $translated : null;
    }

    /**
     * Return success envelope.
     *
     * Note: message is optional; if null, it will be translated by code.
     */
    public static function success(mixed $data = null, string $code = 'OK', ?string $message = null, int $status = Response::HTTP_OK)
    {
        $finalMessage = $message ?? self::translate($code);

        return ApiResponseResource::success($code, $finalMessage, $data)
            ->response()
            ->setStatusCode($status);
    }

    /**
     * Return error envelope.
     *
     * Note: message is optional; if null, it will be translated by code.
     */
    public static function error(string $code, ?string $message = null, int $status = Response::HTTP_BAD_REQUEST, mixed $data = null)
    {
        $finalMessage = $message ?? self::translate($code);

        return ApiResponseResource::error($code, $finalMessage, $data)
            ->response()
            ->setStatusCode($status);
    }
}
