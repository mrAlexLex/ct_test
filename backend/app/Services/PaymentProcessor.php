<?php

namespace App\Services;

use App\Models\Logger;

class PaymentProcessor
{
    private static $instance = null;
    public $userId;

    private function __construct($userId)
    {
        $this->userId = $userId;
    }

    public static function getInstance($userId)
    {
        if (self::$instance === null) {
            self::$instance = new PaymentProcessor($userId);
        }
        return self::$instance;
    }

    public function processCard($payment)
    {
        Logger::info("Using processor for user {$this->userId}");
        $res = file_get_contents("https://example.com/pay?uid={$payment->user_id}&sum={$payment->amount}");
        $payment->status = $res === "OK" ? 'success' : 'fail';
        $payment->save();
        app()->make('events')::dispatch('payment.card', ['uid' => $payment->user_id]);
        echo $payment->status === 'success' ? 'Payment successful!' : 'Error!';
    }

    public function processCrypto($payment)
    {
        Logger::info("Using processor for user {$this->userId}");
        $payment->status = 'processing';
        $payment->save();
        app()->make('events')::dispatch('payment.crypto', ['uid' => $payment->user_id]);
        echo 'Wait for confirmation...';
    }

}
