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
    | Socialite Providers
    |--------------------------------------------------------------------------
    |
    | Uncomment and configure the providers you want to enable.
    | Add the corresponding environment variables to your .env file.
    | The <livewire:socialite /> component auto-detects configured providers.
    |
    */

    'github' => [
        'client_id' => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect' => '/auth/github/callback',
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => '/auth/google/callback',
    ],

    // 'facebook' => [
    //     'client_id' => env('FACEBOOK_CLIENT_ID'),
    //     'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
    //     'redirect' => '/auth/facebook/callback',
    // ],

    // 'twitter-oauth-2' => [
    //     'client_id' => env('TWITTER_CLIENT_ID'),
    //     'client_secret' => env('TWITTER_CLIENT_SECRET'),
    //     'redirect' => '/auth/twitter-oauth-2/callback',
    // ],

    // 'linkedin-openid' => [
    //     'client_id' => env('LINKEDIN_CLIENT_ID'),
    //     'client_secret' => env('LINKEDIN_CLIENT_SECRET'),
    //     'redirect' => '/auth/linkedin-openid/callback',
    // ],

    // 'gitlab' => [
    //     'client_id' => env('GITLAB_CLIENT_ID'),
    //     'client_secret' => env('GITLAB_CLIENT_SECRET'),
    //     'redirect' => '/auth/gitlab/callback',
    // ],

    // 'bitbucket' => [
    //     'client_id' => env('BITBUCKET_CLIENT_ID'),
    //     'client_secret' => env('BITBUCKET_CLIENT_SECRET'),
    //     'redirect' => '/auth/bitbucket/callback',
    // ],

    // 'slack-openid' => [
    //     'client_id' => env('SLACK_OPENID_CLIENT_ID'),
    //     'client_secret' => env('SLACK_OPENID_CLIENT_SECRET'),
    //     'redirect' => '/auth/slack-openid/callback',
    // ],

];
