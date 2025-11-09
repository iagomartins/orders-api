<?php

namespace App\Exceptions;

class UnauthorizedException extends ApiException
{
    public function __construct(string $message = 'Unauthorized', $errors = null)
    {
        parent::__construct($message, 401, $errors);
    }
}

