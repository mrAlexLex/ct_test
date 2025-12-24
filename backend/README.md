## Установка

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

## Конфигурация

Добавьте в `.env`:

```env
# Card payments
PAYMENT_CARD_API_URL=https://api.cardprovider.com/charge
PAYMENT_CARD_API_KEY=your_api_key

# Crypto payments
PAYMENT_CRYPTO_API_URL=https://api.cryptoprovider.com/payment
PAYMENT_CRYPTO_CONFIRMATIONS=3
```

## API

### Создать платёж

```http
POST /api/payments
Content-Type: application/json

{
    "user_id": 1,
    "amount": 99.99,
    "method": "card",
    "currency": "USD",
    "metadata": {}
}
```

**Ответ:**

```json
{
    "success": true,
    "status": "success",
    "transaction_id": "card_abc123",
    "message": "Card payment processed successfully",
    "metadata": {}
}
```

### Получить доступные методы оплаты

```http
GET /api/payments/methods
```

**Ответ:**

```json
{
    "data": [
        { "name": "card" },
        { "name": "crypto" }
    ]
}
```

## Архитектура

```
app/
├── Contracts/
│   └── PaymentGatewayInterface.php
├── DTO/
│   ├── PaymentData.php
│   └── PaymentResult.php
├── Gateways/
│   ├── AbstractPaymentGateway.php
│   ├── CardPaymentGateway.php
│   ├── CryptoPaymentGateway.php
│   └── PaymentGatewayFactory.php
├── Http/
│   ├── Controllers/PaymentController.php
│   ├── Requests/PaymentRequest.php
│   └── Resources/
├── Models/Payment/Payment/
│   ├── Enums/PaymentStatusEnum.php
│   ├── Payment.php
│   ├── PaymentConstants.php
│   └── PaymentScopes.php
├── Rules/
│   └── SupportedPaymentMethodRule.php
└── Services/
    └── PaymentService.php
```

## Добавление нового метода оплаты

### 1. Создать Gateway

```php
// app/Gateways/PayPalPaymentGateway.php

namespace App\Gateways;

use App\DTO\PaymentData;
use App\DTO\PaymentResult;

class PayPalPaymentGateway extends AbstractPaymentGateway
{
    protected string $method = 'paypal';

    public function __construct(
        private string $clientId,
        private string $secret,
    ) {}

    public function process(PaymentData $data): PaymentResult
    {
        // Реализация
    }
}
```

### 2. Зарегистрировать в AppServiceProvider

```php
// app/Providers/AppServiceProvider.php

$this->app->singleton(PayPalPaymentGateway::class, function () {
    return new PayPalPaymentGateway(
        clientId: config('services.payments.paypal.client_id'),
        secret: config('services.payments.paypal.secret'),
    );
});

$this->app->tag([
    CardPaymentGateway::class,
    CryptoPaymentGateway::class,
    PayPalPaymentGateway::class, // Добавить
], 'payment.gateways');
```

### 3. Добавить конфигурацию

```php
// config/services.php

'payments' => [
    'paypal' => [
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'secret' => env('PAYPAL_SECRET'),
    ],
],
```

## Docker

```bash
docker-compose up -d
```
