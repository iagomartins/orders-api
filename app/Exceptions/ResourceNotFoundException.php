<?php

namespace App\Exceptions;

class ResourceNotFoundException extends ApiException
{
    public function __construct(string $message = 'Resource not found', $errors = null)
    {
        parent::__construct($message, 404, $errors);
    }
}

