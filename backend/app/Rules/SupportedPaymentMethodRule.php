<?php

namespace App\Rules;

use App\Gateways\PaymentGatewayFactory;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SupportedPaymentMethodRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $factory = app(PaymentGatewayFactory::class);

        if (!$factory->supports($value)) {
            $fail(sprintf('Unsupported payment method: %s.', $value));
        }
    }
}
