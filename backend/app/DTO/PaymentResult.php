<?php

namespace App\DTO;

class PaymentResult
{
    public function __construct(
        public bool    $success,
        public string  $status,
        public ?string $transactionId = null,
        public ?string $message = null,
        public array   $metadata = [],
    )
    {
    }

    public static function success(
        string $transactionId,
        string $message = 'Payment processed successfully',
        array  $metadata = []
    ): self
    {
        return new self(
            success: true,
            status: 'success',
            transactionId: $transactionId,
            message: $message,
            metadata: $metadata,
        );
    }

    public static function pending(
        string $message = 'Payment is being processed',
        array  $metadata = []
    ): self
    {
        return new self(
            success: true,
            status: 'pending',
            transactionId: null,
            message: $message,
            metadata: $metadata,
        );
    }

    public static function failed(
        string $message = 'Payment failed',
        array  $metadata = []
    ): self
    {
        return new self(
            success: false,
            status: 'failed',
            transactionId: null,
            message: $message,
            metadata: $metadata,
        );
    }

    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'status' => $this->status,
            'transaction_id' => $this->transactionId,
            'message' => $this->message,
            'metadata' => $this->metadata,
        ];
    }
}

