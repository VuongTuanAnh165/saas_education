<?php

namespace App\Exceptions;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class BusinessException extends RuntimeException
{
    /**
     * BusinessException represents a domain error that client can handle.
     *
     * - errorCode MUST be a stable API code (SCREAMING_SNAKE_CASE).
     * - status MUST use Response::HTTP_*.
     * - message is optional; if empty, ApiResponse will translate by code.
     */
    public function __construct(
        public readonly string $errorCode,
        public readonly int $status = Response::HTTP_BAD_REQUEST,
        string $message = ''
    ) {
        parent::__construct($message);
    }
}
