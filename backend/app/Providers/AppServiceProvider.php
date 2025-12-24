<?php

namespace App\Providers;

use App\Gateways\CardPaymentGateway;
use App\Gateways\CryptoPaymentGateway;
use App\Gateways\PaymentGatewayFactory;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerPaymentGateways();
    }

    public function boot(): void
    {
        //
    }

    private function registerPaymentGateways(): void
    {
        $this->app->singleton(CardPaymentGateway::class, function () {
            return new CardPaymentGateway(
                apiUrl: config('services.payments.card.url'),
                apiKey: config('services.payments.card.key'),
            );
        });

        $this->app->singleton(CryptoPaymentGateway::class, function () {
            return new CryptoPaymentGateway(
                apiUrl: config('services.payments.crypto.url'),
                requiredConfirmations: (int)config('services.payments.crypto.confirmations'),
            );
        });

        $this->app->tag([
            CardPaymentGateway::class,
            CryptoPaymentGateway::class,
        ], 'payment.gateways');

        $this->app->singleton(PaymentGatewayFactory::class, function ($app) {
            return new PaymentGatewayFactory(
                $app->tagged('payment.gateways')
            );
        });
    }
}
