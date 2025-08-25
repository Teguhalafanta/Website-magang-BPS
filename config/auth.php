<?php

return [
    'guards' => [
        'web' => [ // Guard untuk mahasiswa
            'driver' => 'session',
            'provider' => 'users',
        ],
    ],

    'providers' => [
        'users' => [ // Mahasiswa
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
    ]
];
