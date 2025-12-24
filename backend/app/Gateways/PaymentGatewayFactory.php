<?php

namespace App\Gateways;

use App\Contracts\PaymentGatewayInterface;
use App\Exceptions\UnsupportedPaymentMethodException;

class PaymentGatewayFactory
{
    public function __construct(
        private iterable $gateways
    )
    {
    }

    public function make(string $method): PaymentGatewayInterface
    {
        foreach ($this->gateways as $gateway) {
            if ($gateway->supports($method)) {
                return $gateway;
            }
        }

        throw new UnsupportedPaymentMethodException($method);
    }

    public function availableMethods(): array
    {
        $methods = [];

        foreach ($this->gateways as $gateway) {
            $methods[] = $gateway->getMethod();
        }

        return $methods;
    }

    public function supports(string $method): bool
    {
        foreach ($this->gateways as $gateway) {
            if ($gateway->supports($method)) {
                return true;
            }
        }

        return false;
    }
}

