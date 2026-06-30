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

    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook' => [
            'secret' => env('STRIPE_WEBHOOK_SECRET'),
        ],
    ],

    'google' => [
        'places_api_key' => env('GOOGLE_PLACES_API_KEY'),
    ],

    'sumup' => [
        // Partner-level (Dayaa) credentials — embedded in every Reader Checkout for partner attribution.
        'app_id' => env('SUMUP_APP_ID'),
        'affiliate_key' => env('SUMUP_AFFILIATE_KEY'),

        // Global API config
        'base_url' => env('SUMUP_BASE_URL', 'https://api.sumup.com'),
        'test_mode' => env('SUMUP_TEST_MODE', true),

        // Legacy/global merchant credentials — kept for backwards compat with the existing
        // SumUpService until it's rewritten in Phase 3. Per-organization keys live on the
        // organizations table (sumup_api_key, sumup_merchant_code).
        'api_key' => env('SUMUP_API_KEY'),
        'merchant_code' => env('SUMUP_MERCHANT_CODE'),
        'webhook_secret' => env('SUMUP_WEBHOOK_SECRET'),
    ],

];
