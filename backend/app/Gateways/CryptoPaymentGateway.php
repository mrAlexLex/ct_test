<?php

namespace App\Gateways;

use App\DTO\PaymentData;
use App\DTO\PaymentResult;
use App\Models\Payment;
use app\Models\Payment\Payment\Enums\PaymentStatusEnum;
use Illuminate\Support\Arr;

class CryptoPaymentGateway extends AbstractPaymentGateway
{
    protected string $method = 'crypto';

    public function __construct(
        private string $apiUrl,
        private int    $requiredConfirmations = 3,
    )
    {
    }

    public function process(PaymentData $data): PaymentResult
    {
        $this->log('Processing crypto payment', [
            'user_id' => $data->userId,
            'amount' => $data->amount,
        ]);

        $payment = $this->createPaymentRecord($data);

        try {
            $response = $this->request($this->apiUrl, [
                'user_id' => $data->userId,
                'amount' => $data->amount,
                'currency' => $data->currency,
                'confirmations' => $this->requiredConfirmations,
            ]);

            if (Arr::get($response, 'success')) {
                $walletAddress = Arr::get($response, 'body.wallet_address');
                $paymentId = Arr::get($response, 'body.payment_id') ?? uniqid('crypto_');

                $payment->update([
                    'metadata' => array_merge($payment->metadata ?? [], [
                        'wallet_address' => $walletAddress,
                        'required_confirmations' => $this->requiredConfirmations,
                    ]),
                ]);

                $this->log('Crypto payment initiated', [
                    'payment_id' => $paymentId,
                    'wallet_address' => $walletAddress,
                ]);

                return PaymentResult::pending(
                    message: 'Awaiting blockchain confirmation',
                    metadata: [
                        'payment_id' => $paymentId,
                        'wallet_address' => $walletAddress,
                        'required_confirmations' => $this->requiredConfirmations,
                    ],
                );
            }

            $this->updatePaymentStatus($payment, PaymentStatusEnum::STATUS_FAILED);

            return PaymentResult::failed(
                message: Arr::get($response, 'body.error') ?? 'Failed to initiate crypto payment',
            );

        } catch (\Throwable $e) {
            $this->updatePaymentStatus($payment, PaymentStatusEnum::STATUS_FAILED);

            $this->log('Crypto payment exception', [
                'error' => $e->getMessage(),
            ]);

            return PaymentResult::failed(
                message: sprintf('Payment processing error: %s', $e->getMessage()),
            );
        }
    }
}

