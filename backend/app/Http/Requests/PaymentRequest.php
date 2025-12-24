<?php

namespace App\Http\Requests;

use App\DTO\PaymentData;
use App\Rules\SupportedPaymentMethodRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'integer',
                Rule::exists('users', 'id'),
            ],
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
                'max:999999.99'
            ],
            'method' => [
                'required',
                'string',
                'max:50',
                new SupportedPaymentMethodRule(),
            ],
            'currency' => [
                'required',
                'string',
                'size:3'
            ],
            'metadata' => [
                'nullable',
                'array'
            ],
        ];
    }

    public function getPaymentData(): PaymentData
    {
        return new PaymentData(
            userId: $this->input('user_id'),
            amount: $this->input('amount'),
            method: $this->input('method'),
            currency: $this->input('currency'),
            metadata: $this->input('metadata', []),
        );
    }
}
