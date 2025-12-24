<?php

namespace app\Models\Payment\Payment;

use app\Models\Payment\Payment\Enums\PaymentStatusEnum;
use Illuminate\Database\Eloquent\Builder;

trait PaymentScopes
{
    public function scopeSuccessful(Builder $query)
    {
        return $query->where('status', PaymentStatusEnum::STATUS_SUCCESS->value);
    }

    public function scopePending(Builder $query)
    {
        return $query->where('status', PaymentStatusEnum::STATUS_PENDING->value);
    }

    public function scopeByMethod(Builder $query, string $method)
    {
        return $query->where('method', $method);
    }
}
