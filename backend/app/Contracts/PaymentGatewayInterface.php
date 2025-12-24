<?php

namespace App\Contracts;

use App\DTO\PaymentData;
use App\DTO\PaymentResult;

interface PaymentGatewayInterface
{
    /**
     * Process payment through the gateway
     */
    public function process(PaymentData $payment): PaymentResult;

    /**
     * Check if this gateway supports the given payment method
     */
    public function supports(string $method): bool;

    /**
     * Get the payment method identifier
     */
    public function getMethod(): string;
}

