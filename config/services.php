<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    'github' => [
        'client_id' => '627352253ba0227e584a',
        'client_secret' => '2da355d4d3c1a4e5880974c4df43a6ab728831bb',
        'redirect' =>  env('CALLBACK_GITHUB'),
    ],
    'facebook' => [
        'client_id' => '1534476963302469',
        'client_secret' => 'e0a31eca518282c2e7dff33440708b19',
        'redirect' =>  env('CALLBACK_FACEBOOK'),
    ],
    'google' => [
        'client_id' => 'tracnghiemonline-191711',
        'client_secret' => 'AIzaSyC3Gnvgvp6rcc7vMwgSEPbESaLcE3uubLQ',
        'redirect' =>  '',
    ]
];
