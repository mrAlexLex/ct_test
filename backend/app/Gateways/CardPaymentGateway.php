<?php

namespace App\Gateways;

use App\DTO\PaymentData;
use App\DTO\PaymentResult;
use App\Models\Payment;
use app\Models\Payment\Payment\Enums\PaymentStatusEnum;
use Illuminate\Support\Arr;

class CardPaymentGateway extends AbstractPaymentGateway
{
    protected string $method = 'card';

    public function __construct(
        private string $apiUrl,
        private string $apiKey,
    )
    {
    }

    public function process(PaymentData $data): PaymentResult
    {
        $this->log('Processing card payment', [
            'user_id' => $data->userId,
            'amount' => $data->amount,
        ]);

        $payment = $this->createPaymentRecord($data, PaymentStatusEnum::STATUS_PROCESSING);

        try {
            $response = $this->request($this->apiUrl, [
                'user_id' => $data->userId,
                'amount' => $data->amount,
                'currency' => $data->currency,
                'api_key' => $this->apiKey,
            ]);

            if (Arr::get($response, 'success')) {
                $transactionId = Arr::get($response, 'body.transaction_id') ?? uniqid('card_');

                $this->updatePaymentStatus($payment, PaymentStatusEnum::STATUS_SUCCESS, $transactionId);

                $this->log('Card payment successful', [
                    'transaction_id' => $transactionId,
                ]);

                return PaymentResult::success(
                    transactionId: $transactionId,
                    message: 'Card payment processed successfully',
                );
            }

            $this->updatePaymentStatus($payment, PaymentStatusEnum::STATUS_FAILED);

            $this->log('Card payment failed', [
                'response' => $response,
            ]);

            return PaymentResult::failed(
                message: Arr::get($response, 'body.error') ?? 'Card payment failed',
            );

        } catch (\Throwable $e) {
            $this->updatePaymentStatus($payment, PaymentStatusEnum::STATUS_FAILED);

            $this->log('Card payment exception', [
                'error' => $e->getMessage(),
            ]);

            return PaymentResult::failed(
                message: sprintf('Payment processing error: %s', $e->getMessage()),
            );
        }
    }
}

