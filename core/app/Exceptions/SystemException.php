<?php

namespace App\Exceptions;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class SystemException extends RuntimeException
{
    public function __construct(
        public readonly string $errorCode,
        public readonly int $status = Response::HTTP_INTERNAL_SERVER_ERROR,
        string $message = ''
    ) {
        parent::__construct($message !== '' ? $message : $errorCode);
    }
}
