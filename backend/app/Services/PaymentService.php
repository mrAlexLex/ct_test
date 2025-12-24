<?php

namespace App\Services;

use App\DTO\PaymentData;
use App\DTO\PaymentResult;
use App\Gateways\PaymentGatewayFactory;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function __construct(
        private PaymentGatewayFactory $gatewayFactory
    )
    {
    }

    public function process(PaymentData $paymentData): PaymentResult
    {
        Log::info('Processing payment', [
            'user_id' => $paymentData->userId,
            'amount' => $paymentData->amount,
            'method' => $paymentData->method,
        ]);

        $gateway = $this->gatewayFactory->make($paymentData->method);

        return $gateway->process($paymentData);
    }

    public function availableMethods(): array
    {
        return $this->gatewayFactory->availableMethods();
    }

    public function supportsMethod(string $method): bool
    {
        return $this->gatewayFactory->supports($method);
    }
}
