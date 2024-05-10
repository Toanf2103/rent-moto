<?php
return [
    'role' => [
        'admin' => 'admin',
        'employee' => 'employee',
        'user' => 'user',
    ],
    'date_format' => 'Y-m-d',
    'path' => [
        'moto_image' => 'moto'
    ],
    'images' => [
        'moto' => [
            'max' => 5
        ]
    ],
    'regex' => [
        'license_plate' => '/^\d{2}[A-Z][A-Z\d]-\d{4,5}$/',
    ],
    'max_date_rent' => 10,
    'slack' => [
        'channels' => [
            'order' => 'order',
            'cron_job' => 'cron-job',
        ]
    ],
    'fe' => [
        'url' => env('FE_URL', 'http://localhost:5174'),
        'url_order' => env('FE_URL', 'http://localhost:5174') . env('FE_PATH_ORDER', '/orders/'),
    ],
    'price' => [
        'min' => 50000,
        'max' => 5000000
    ],
    'holiday_format' => 'm-d'
];
