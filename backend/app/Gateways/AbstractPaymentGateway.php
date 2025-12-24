<?php

namespace App\Gateways;

use App\Contracts\PaymentGatewayInterface;
use App\DTO\PaymentData;
use app\Models\Payment\Payment\Enums\PaymentStatusEnum;
use app\Models\Payment\Payment\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

abstract class AbstractPaymentGateway implements PaymentGatewayInterface
{
    protected string $method;

    public function supports(string $method): bool
    {
        return $this->method === $method;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    protected function createPaymentRecord(PaymentData $data, PaymentStatusEnum $status = PaymentStatusEnum::STATUS_PENDING): Payment
    {
        return Payment::query()->create([
            'user_id' => $data->userId,
            'amount' => $data->amount,
            'method' => $data->method,
            'currency' => $data->currency,
            'status' => $status,
            'metadata' => $data->metadata,
        ]);
    }

    protected function updatePaymentStatus(Payment $payment, PaymentStatusEnum $status, ?string $transactionId = null): void
    {
        $payment->update([
            'status' => $status,
            'transaction_id' => $transactionId,
        ]);
    }

    protected function log(string $message, array $context = []): void
    {
        $text = sprintf('[Payment:%s] %s', $this->method, $message);

        Log::info($text, $context);
    }

    protected function request(string $url, array $data = [], string $method = 'POST'): array
    {
        $response = Http::baseUrl($url)->$method($url, $data);

        return [
            'success' => $response->successful(),
            'status' => $response->status(),
            'body' => $response->json() ?? [],
        ];
    }
}

