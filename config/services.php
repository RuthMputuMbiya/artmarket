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
    | SHWARY PAYMENT SERVICE
    |--------------------------------------------------------------------------
    |
    | Configuration pour l'intégration de l'API de paiement Shwary
    |
    */
 'shwary' => [
    'merchant_id' => env('SHWARY_MERCHANT_ID'),
    'merchant_ikey' => env('SHWARY_MERCHANT_IKEY'),
    'callback_url' => env('SHWARY_CALLBACK_URL'),
    'sandbox' => env('SHWARY_SANDBOX', true),
    'timeout' => env('SHWARY_TIMEOUT', 60),
    'api_url' => env('SHWARY_API_URL', 'https://sandbox.shwary.com/api/v1'),
],
];