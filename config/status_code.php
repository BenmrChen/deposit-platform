<?php

return [
    // Success
    '000-00' => [
        'status'  => '200',
        'message' => 'Success.',
    ],
    // Common
    '000-01' => [
        'status'  => '401',
        'message' => 'Unauthenticated.',
    ],
    '000-02' => [
        'status'  => '404',
        'message' => 'Not found.',
    ],
    '000-03' => [
        'status'  => '405',
        'message' => 'Method not allowed.',
    ],
    '000-04' => [
        'status'  => '422',
        'message' => 'Validation error.',
    ],
    '000-05' => [
        'status'  => '429',
        'message' => 'Too many attempts.',
    ],
    '000-06' => [
        'status'  => '403',
        'message' => 'Forbidden.',
    ],
    // Orders
    '001-01' => [
        'status'  => '404',
        'message' => 'Order not found.',
    ],
    '001-02' => [
        'status'  => '403',
        'message' => 'The client id is not match.',
    ],
    '001-03' => [
        'status'  => '403',
        'message' => 'The feature is disabled.',
    ],
    '001-04' => [
        'status'  => '403',
        'message' => 'The symbol is disabled.',
    ],
    '001-05' => [
        'status'  => '422',
        'message' => 'The user is not bound.',
    ],
    '001-06' => [
        'status'  => '422',
        'message' => 'The balance is insufficient.',
    ],
    '001-07' => [
        'status'  => '422',
        'message' => 'Order id exists.',
    ],
    // Client Balances
    '002-01' => [
        'status'  => '403',
        'message' => 'Invalid parameter(s).',
    ],
    // Users
    '003-01' => [
        'status'  => '422',
        'message' => 'Unable to bind.',
    ],
    // User Withdrawal
    '004-01' => [
        'status'  => '422',
        'message' => 'The balance is insufficient.',
    ],
    // Maintenance
    '005-01' => [
        'status'  => '599',
        'message' => 'IN MAINTENANCE',
    ],
];
