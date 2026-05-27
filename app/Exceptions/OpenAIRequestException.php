<?php

namespace App\Exceptions;

use RuntimeException;
use Throwable;

class OpenAIRequestException extends RuntimeException
{
    public function __construct(
        string $message,
        private readonly int $status = 500,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $status, $previous);
    }

    public function status(): int
    {
        return $this->status;
    }
}

