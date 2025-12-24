<?php

namespace App\Exceptions;

use Exception;

class UnsupportedPaymentMethodException extends Exception
{
    public function __construct(string $method)
    {
        parent::__construct(sprintf('Unsupported payment method: %s', $method));
    }
}

