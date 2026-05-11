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

    'celcom' => [
        'api_key' => env('CELCOM_API_KEY'),
        'shortcode' => env('CELCOM_SHORTCODE'),
        'partner_id' => env('CELCOM_PARTNER_ID'),
        'base_url' => env('CELCOM_BASE_URL', 'https://isms.celcomafrica.com/api/services/sendsms/'),
    ],

    'mpesa' => [
        'base_url' => env('DARAJA_BASE_URL', env('MPESA_BASE_URL', 'https://sandbox.safaricom.co.ke')),
        'consumer_key' => env('DARAJA_CONSUMER_KEY', env('MPESA_CONSUMER_KEY')),
        'consumer_secret' => env('DARAJA_CONSUMER_SECRET', env('MPESA_CONSUMER_SECRET')),
        'shortcode' => env('DARAJA_SHORTCODE', env('MPESA_SHORTCODE')),
        'passkey' => env('DARAJA_PASSKEY', env('MPESA_PASSKEY')),
        'transaction_type' => env('DARAJA_TRANSACTION_TYPE', env('MPESA_TRANSACTION_TYPE', 'CustomerPayBillOnline')),
        'callback_url' => env('DARAJA_CALLBACK_URL', env('MPESA_CALLBACK_URL')),
    ],

];
