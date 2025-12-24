<?php

namespace App\Services;

use App\Models\PaymentModel;

class PaymentService
{
    public function handle($uid, $sum, $method)
    {
        $logger = app()->make('logger');
        $processor = PaymentProcessor::getInstance($uid);
        $processor->user_id = $uid;

        $logger::info("Processing $method payment for $uid");

        $payment = new PaymentModel();
        $payment->user_id = $uid;
        $payment->amount = $sum;
        $payment->method = $method;
        $payment->created_at = date('Y-m-d H:i:s');

        if ($method === 'card') {
            $processor->processCard($payment);
        } elseif ($method === 'crypto') {
            $processor->processCrypto($payment);
        } else {
            $logger::info("Unknown payment method: $method");
            echo 'Unknown method';
        }
    }
}
