<?php

namespace App\Http\Resources;

use App\Http\Middleware\RequestIdMiddleware;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiResponseResource extends JsonResource
{
    public function __construct(
        private readonly bool $success,
        private readonly string $code,
        private readonly ?string $message,
        private readonly mixed $data,
    ) {
        parent::__construct(null);
    }

    public static function success(string $code, ?string $message, mixed $data): self
    {
        return new self(true, $code, $message, $data);
    }

    public static function error(string $code, ?string $message, mixed $data): self
    {
        return new self(false, $code, $message, $data);
    }

    public function toArray(Request $request): array
    {
        $data = $this->data;

        if ($data === null) {
            $data = (object) [];
        }

        $requestId = $request->attributes->get(RequestIdMiddleware::ATTRIBUTE_NAME);
        if (!is_string($requestId) || trim($requestId) === '') {
            $requestId = $request->headers->get(RequestIdMiddleware::HEADER_NAME);
        }

        return [
            'success' => $this->success,
            'code' => $this->code,
            'message' => $this->message,
            'data' => $data,
            'meta' => [
                'request_id' => is_string($requestId) ? $requestId : null,
                'timestamp' => now()->toISOString(),
            ],
        ];
    }
}
