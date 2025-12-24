<?php

namespace App\Http\Resources;

use App\DTO\PaymentResult;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResultResource extends JsonResource
{
    public function toArray(Request $request): array
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

