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

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'geo_gouv' => [
        'base_url' => 'https://geo.api.gouv.fr',
        'timeout' => 20,
    ],

    'serpapi' => [
        'key' => env('SERPAPI_KEY'),
        'base_url' => env('SERPAPI_BASE_URL', 'https://serpapi.com/search'),
        'timeout' => (int) env('SERPAPI_TIMEOUT', 60),
        'google_domain' => 'google.fr',
        'gl' => 'fr',
        'hl' => 'fr',
        'no_cache' => false,
    ],

    'openai_local' => [
        'default_model' => env('OPENAI_DEFAULT_MODEL', 'gpt-4o-mini'),
        'timeout' => (int) env('OPENAI_REQUEST_TIMEOUT', 120),
    ],

    'openweather' => [
        'key' => env('OPENWEATHER_API_KEY'),
        'base_url' => env('OPENWEATHER_BASE_URL', 'https://api.openweathermap.org'),
        'timeout' => (int) env('OPENWEATHER_TIMEOUT', 30),
    ],

];
