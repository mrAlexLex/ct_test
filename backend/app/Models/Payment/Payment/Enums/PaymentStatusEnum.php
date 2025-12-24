<?php

namespace app\Models\Payment\Payment\Enums;

enum PaymentStatusEnum: string
{
    case STATUS_PENDING = 'pending';
    case STATUS_PROCESSING = 'processing';
    case STATUS_SUCCESS = 'success';
    case STATUS_FAILED = 'failed';
    case STATUS_REFUNDED = 'refunded';

    public function isSuccessful(): bool
    {
        return $this === self::STATUS_SUCCESS;
    }

    public function isPending(): bool
    {
        return $this === self::STATUS_PENDING;
    }
}
