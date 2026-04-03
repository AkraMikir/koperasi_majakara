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
    | Fonnte WhatsApp API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Fonnte WhatsApp Business API integration
    | Used for sending OTP and notifications via WhatsApp
    |
    */
    'fonnte' => [
        'api_key' => env('FONNTE_API_KEY'),
        'api_url' => env('FONNTE_API_URL', 'https://api.fonnte.com/send'),
        'sender_number' => env('FONNTE_SENDER_NUMBER', '08139552626'),
        // SSL: set false hanya untuk development lokal jika error "unable to get local issuer certificate"
        'verify_ssl' => env('FONNTE_VERIFY_SSL', true),
        // Opsional: path ke cacert.pem (unduh dari https://curl.se/ca/cacert.pem)
        'ssl_cert_path' => env('FONNTE_SSL_CERT_PATH'),
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

];
