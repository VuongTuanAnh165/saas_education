<?php

namespace App\Exceptions;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class SystemException extends RuntimeException
{
    /**
     * SystemException represents an unexpected system-level failure.
     *
     * - errorCode MUST be a stable API code (SCREAMING_SNAKE_CASE).
     * - status MUST use Response::HTTP_*.
     * - message is optional; global handler SHOULD NOT expose sensitive details.
     */
    public function __construct(
        public readonly string $errorCode,
        public readonly int $status = Response::HTTP_INTERNAL_SERVER_ERROR,
        string $message = ''
    ) {
        parent::__construct($message);
    }
}
