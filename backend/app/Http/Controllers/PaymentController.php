<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Http\Resources\PaymentMethodResource;
use App\Http\Resources\PaymentResultResource;
use App\Services\PaymentService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PaymentController extends Controller
{
    public function __construct(
        private PaymentService $paymentService
    )
    {
    }

    public function pay(PaymentRequest $request): PaymentResultResource
    {
        $result = $this->paymentService->process($request->getPaymentData());

        return new PaymentResultResource($result);
    }

    public function methods(): AnonymousResourceCollection
    {
        return PaymentMethodResource::collection($this->paymentService->availableMethods());
    }
}
