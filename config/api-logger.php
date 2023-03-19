<?php

return [
    'enabled'   => env('API_LOGGER_ENABLED', false),
    'formatter' => SevenSenses\ApiLogger\Formatter\StackDriverFormatter::class,
    'request'   => [
        'log_headers'   => [
            'host',
            'x-timestamp',
            'x-signature',
            'x-client-id',
            'x-requested-with',
        ],
        'except_inputs' => [
            'token',
            'password',
        ],
    ],
    'response'  => [
        'enabled'       => env('API_LOGGER_LOG_RESPONSE', false),
        'except_fields' => [],
    ],
];
