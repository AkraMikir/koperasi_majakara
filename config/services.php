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
    | Hostinger Email Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Hostinger Email API (SMTP)
    | Used for sending OTP and notifications via Email
    |
    */
    'hostinger_email' => [
        'api_key'             => env('HOSTINGER_EMAIL_API_KEY'),
        'mailbox_resource_id' => env('HOSTINGER_MAILBOX_RESOURCE_ID', 'ACfcce24a5a4159cb4284a002e888d'),
        'from'                => env('MAIL_FROM_ADDRESS', 'koperasi@majakara.com'),
        'name'                => env('MAIL_FROM_NAME', 'Koperasi Majakara'),
    ],

    /*
    |--------------------------------------------------------------------------
    | OTP Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for OTP (One-Time Password) system
    | Used for registration and transaction verification
    |
    */
    'otp' => [
        'length' => env('OTP_LENGTH', 6),
        'expiry_minutes' => env('OTP_EXPIRY_MINUTES', 1), // 1 menit
        'max_attempts' => env('OTP_MAX_ATTEMPTS', 3),
        'cooldown_seconds' => env('OTP_COOLDOWN_SECONDS', 60),
    ],

    'gemini' => [
        'api_key' => env('GEMINI_API_KEY'),
    ],

];
