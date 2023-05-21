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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'facebook' => [
        'client_id' => '369277428593878',  //client face của bạn
        'client_secret' => '39cf42ba7dea0eeeba1a13d47ede9003',  //client app service face của bạn
        'redirect' => 'https://sepnguyenvanhanbro.thegioihaisan.laravel.vn/DoAnCNWeb/user/login-facebook/callback' //callback trả về
    ],

    'google' => [
        'client_id' => '662799365868-ihju2jcvcj6rojoll65hf6v5mqr0gnlq.apps.googleusercontent.com',
        'client_secret' => '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',
        'redirect' => 'https://sepnguyenvanhanbro.thegioihaisan.laravel.vn/DoAnCNWeb/user/login-google/callback'
    ],

];
