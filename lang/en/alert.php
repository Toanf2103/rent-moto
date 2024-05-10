<?php
return [
    'auth' => [
        'login' => [
            'success' => 'Login successful',
            'failed' => 'Login failed',
            'blocked' => 'Your account has been blocked',
            'register' => 'Please check your email to confirm your account'
        ],
        'logout' => [
            'success' => 'Logout successful'
        ],
        'register' => [
            'success' => 'Register successful! Please check your email to confirm your account',
            'failed' => 'Register failed'
        ],
        'refresh' => [
            'failed' => 'Refresh token invalid',
        ],
        'verify' => [
            'failed' => 'Verify failed',
            'success' => 'Verify successful'
        ],
        'reset_password' => [
            'failed' => 'Reset password failed',
            'success' => 'Reset password successful'
        ]
    ],
    'bad_request' => 'Bad request',
    'server_error' => 'Server error',
    'not_found' => 'Not found',
    'forbidden' => 'Unauthenticated.',
    'update_successful' => 'Update successful',
    'delete_successful' => 'Delete successful',
    'import_successful' => 'Import successful',
    'moto' => [
        'images' => [
            'max' => 'Quantity image than maximum(' . config('define.images.moto.max') . ')',
        ],
        'rent' => [
            'failed' => 'Reset password failed',
            'has_been_scheduled' => 'Motos has been scheduled.'
        ]
    ],
    'params' => [
        'invalid' => 'There are some invalid params',
        'rent_package_invalid' => [
            'rent_days_min' => 'Rent day must start from number 1.',
            'rent_days_max' => 'Rent day must start from number ' . config('define.max_date_rent'),
            'rent_days' => 'The milestones must be consecutive.'
        ]
    ],
    'order_issue' => 'Have :quantity order issues of moto:moto_name',
];
