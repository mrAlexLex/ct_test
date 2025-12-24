<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Payment Services Configuration
    |--------------------------------------------------------------------------
    */

    'payments' => [
        'card' => [
            'url' => env('PAYMENT_CARD_API_URL', 'https://api.cardprovider.com/charge'),
            'key' => env('PAYMENT_CARD_API_KEY'),
        ],

        'crypto' => [
            'url' => env('PAYMENT_CRYPTO_API_URL', 'https://api.cryptoprovider.com/payment'),
            'confirmations' => env('PAYMENT_CRYPTO_CONFIRMATIONS', 3),
        ],

        // Add new payment methods configuration here
        // 'paypal' => [
        //     'client_id' => env('PAYPAL_CLIENT_ID'),
        //     'secret' => env('PAYPAL_SECRET'),
        //     'sandbox' => env('PAYPAL_SANDBOX', true),
        // ],
    ],
];
