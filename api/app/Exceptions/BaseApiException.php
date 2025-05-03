<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class BaseApiException extends Exception
{
    protected ?array $errors;

    public function __construct(
        string $message,
        int $code = Response::HTTP_BAD_REQUEST,
        ?array $errors = null
    ) {
        parent::__construct($message, $code);
        $this->errors = $errors;
    }

    public function getErrors(): ?array
    {
        return $this->errors;
    }
}
