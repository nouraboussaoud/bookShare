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

    'huggingface' => [
        'token' => env('HUGGINGFACE_TOKEN'),
    ],

    'groq' => [
        'api_key' => env('GROQ_API_KEY'),
    ],

    'ai_moderation' => [
        'enabled' => env('AI_MODERATION_ENABLED', false),
        'provider' => env('AI_MODERATION_PROVIDER', 'groq'), // groq, openai, perspective, custom
        'api_key' => env('AI_MODERATION_API_KEY'),
        'endpoint' => env('AI_MODERATION_ENDPOINT'),
        'model' => env('AI_MODERATION_MODEL', 'llama3-70b-8192'),
        'timeout' => env('AI_MODERATION_TIMEOUT', 5), // seconds
        'fallback_enabled' => env('AI_MODERATION_FALLBACK_ENABLED', true),
        'max_daily_calls' => env('AI_MODERATION_MAX_DAILY_CALLS', 50), // Reduced from 100 to 50 for more conservative usage
        'call_interval' => env('AI_MODERATION_CALL_INTERVAL', 120), // Increased from 60 to 120 seconds between calls
    ],

    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],

];