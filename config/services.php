<?php

return [
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
    'zoom' => [
        'client_id' => env('ZOOM_CLIENT_ID'),
        'client_secret' => env('ZOOM_CLIENT_SECRET'),
        'account_id' => env('ZOOM_ACCOUNT_ID'),
        'basic_token' => env('ZOOM_BASIC_AUTH'),
        'sdk_key' => env('ZOOM_SDK_CLIENT_ID'),
        'sdk_secret' => env('ZOOM_SDK_CLIENT_SECRET'),
    ],
    'mailersend' => [
        'token' => env('MAILERSEND_API_KEY')
    ],
    'cometchat' => [
        'app_id' => env('COMETCHAT_APP_ID'),
        'auth_key' => env('COMETCHAT_AUTH_KEY'),
        'api_key' => env('COMETCHAT_API_KEY'),
        'region' => env('COMETCHAT_REGION'),
        'widget_id' => env('COMETCHAT_WIDGET_ID'),
    ],
];
