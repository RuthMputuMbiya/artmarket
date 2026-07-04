<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SHWARY MERCHANT CONFIGURATION
    |--------------------------------------------------------------------------
    */
    
    'merchant_ikey' => env('SHWARY_MERCHANT_IKEY'),
    'merchant_id' => env('SHWARY_MERCHANT_ID'),
    'callback_url' => env('SHWARY_CALLBACK_URL'),
    'sandbox' => env('SHWARY_SANDBOX', true),
    'timeout' => env('SHWARY_TIMEOUT', 30),
    
    /*
    |--------------------------------------------------------------------------
    | API ENDPOINTS
    |--------------------------------------------------------------------------
    */
    'endpoints' => [
        'sandbox' => 'https://sandbox.shwary.com/api/v1',
        'production' => 'https://api.shwary.com/v1',
    ],
    
    /*
    |--------------------------------------------------------------------------
    | PAYMENT OPTIONS
    |--------------------------------------------------------------------------
    */
    'options' => [
        'currency' => 'CDF',
        'language' => 'fr',
        'methods' => ['card', 'mobile_money', 'bank_transfer'],
    ],
];