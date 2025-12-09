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
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
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

    'sepay' => [
        'base_url' => env('SEPAY_BASE_URL', 'https://qr.sepay.vn'),
        'api_key' => env('SEPAY_API_KEY') ?: env('SEPAY_API_TOKEN'),
        'account_number' => env('SEPAY_ACC_NO') ?: env('SEPAY_ACC'),
        'account_name' => env('SEPAY_ACC_NAME') ?: env('SEPAY_ACCOUNT_NAME'),
        'bank_code' => env('SEPAY_BANK_CODE', env('SEPAY_BANK_NAME', 'MBBank')),
        'content_prefix' => env('SEPAY_CONTENT_PREFIX', env('SEPAY_MATCH_PATTERN', '')),
        'qr_ttl_minutes' => env('SEPAY_QR_TTL', 10),
        'webhook_token' => env('SEPAY_WEBHOOK_TOKEN'),
    ],

];
