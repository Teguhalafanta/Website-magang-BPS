<?php

return [

<<<<<<< HEAD
=======
    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

>>>>>>> 0b7f3e833f23f0e48309edc1583573f5e92f7594
    'guards' => [
        'web' => [ // Guard untuk mahasiswa
            'driver' => 'session',
            'provider' => 'users',
        ],

<<<<<<< HEAD
        'mahasiswa' => [
            'driver' => 'session',
            'provider' => 'mahasiswas',
=======
        'dosen' => [ // Guard untuk dosen
            'driver' => 'session',
            'provider' => 'dosens',
>>>>>>> 0b7f3e833f23f0e48309edc1583573f5e92f7594
        ],
    ],

    'providers' => [
        'users' => [ // Mahasiswa
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],

<<<<<<< HEAD
        'mahasiswas' => [
            'driver' => 'eloquent',
            'model' => App\Models\Mahasiswa::class,
        ],
    ],
=======
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

>>>>>>> 0b7f3e833f23f0e48309edc1583573f5e92f7594
];
