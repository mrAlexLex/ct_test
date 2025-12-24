<?php

namespace App\DTO;

use app\Models\Payment\Payment\Payment;

class PaymentData
{
    public function __construct(
        public int     $userId,
        public float   $amount,
        public string  $method,
        public ?string $currency = 'USD',
        public array   $metadata = [],
    )
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            userId: (int)$data['user_id'],
            amount: (float)$data['amount'],
            method: $data['method'],
            currency: $data['currency'] ?? Payment::DEFAULT_CURRENCY,
            metadata: $data['metadata'] ?? [],
        );
    }

    public function toArray(): array
    {
        return [
            'user_id' => $this->userId,
            'amount' => $this->amount,
            'method' => $this->method,
            'currency' => $this->currency,
            'metadata' => $this->metadata,
        ];
    }
}

