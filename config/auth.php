<?php

return [

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    'guards' => [
        'web' => [ // Guard untuk default (admin)
            'driver' => 'session',
            'provider' => 'users',
        ],

        'pelajar' => [ // Guard untuk pelajar
            'driver' => 'session',
            'provider' => 'pelajars',
        ],
    ],

    'providers' => [
        'users' => [ // User default
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        'pelajars' => [ // Provider untuk pelajar
            'driver' => 'eloquent',
            'model' => App\Models\Pelajar::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        'pelajars' => [
            'provider' => 'pelajars',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];
