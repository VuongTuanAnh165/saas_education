<?php

namespace App\Http\Responses;

use App\Http\Resources\ApiResponseResource;
use Symfony\Component\HttpFoundation\Response;

class ApiResponse
{
    private static function translate(string $code): ?string
    {
        $key = strtolower($code);
        $key = str_replace('-', '_', $key);
        $key = preg_replace('/[^a-z0-9_]+/', '_', $key) ?? $key;
        $key = trim($key, '_');

        $translated = trans('api.' . $key);

        return $translated !== 'api.' . $key ? $translated : null;
    }

    public static function success(mixed $data = null, string $code = 'OK', ?string $message = null, int $status = Response::HTTP_OK)
    {
        $finalMessage = $message;

        if ($finalMessage === null) {
            $finalMessage = self::translate($code);
        }

        return ApiResponseResource::success($code, $finalMessage, $data)
            ->response()
            ->setStatusCode($status);
    }

    public static function error(string $code, ?string $message = null, int $status = Response::HTTP_BAD_REQUEST, mixed $data = null)
    {
        $finalMessage = $message;

        if ($finalMessage === null) {
            $finalMessage = self::translate($code);
        }

        return ApiResponseResource::error($code, $finalMessage, $data)
            ->response()
            ->setStatusCode($status);
    }
}
