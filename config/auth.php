<?php

return [

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    'guards' => [
        'web' => [ // Guard untuk default user
            'driver' => 'session',
            'provider' => 'users',
        ],

        'mahasiswa' => [ // Guard untuk mahasiswa
            'driver' => 'session',
            'provider' => 'mahasiswas',
        ],

        'dosen' => [ // Guard untuk dosen
            'driver' => 'session',
            'provider' => 'dosens',
        ],
    ],

    'providers' => [
        'users' => [ // User default (misalnya admin)
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

        'mahasiswas' => [
            'driver' => 'eloquent',
            'model' => App\Models\Mahasiswa::class,
        ],

        'dosens' => [
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

        'mahasiswas' => [
            'provider' => 'mahasiswas',
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
