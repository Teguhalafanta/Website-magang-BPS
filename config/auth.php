<?php

return [

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    'guards' => [
        'web' => [ // Guard untuk mahasiswa
            'driver' => 'session',
            'provider' => 'users',
        ],

        'dosen' => [ // Guard untuk dosen
            'driver' => 'session',
            'provider' => 'dosens',
        ],
    ],

    'providers' => [
        'users' => [ // Mahasiswa
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        'dosens' => [ // Dosen
            'driver' => 'eloquent',
            'model' => App\Models\Dosen::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],

        'dosens' => [
            'provider' => 'dosens',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];
