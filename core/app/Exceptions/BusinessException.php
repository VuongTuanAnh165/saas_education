<?php

namespace App\Exceptions;

use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class BusinessException extends RuntimeException
{
    public function __construct(
        public readonly string $errorCode,
        public readonly int $status = Response::HTTP_BAD_REQUEST,
        string $message = ''
    ) {
        parent::__construct($message !== '' ? $message : $errorCode);
    }
}
