<?php

return [
    'bootstrap_file' => null,
    'base_domain'    => env('ROUTER_BASE_DOMAIN', 'localhost'),
    'doc_generate'   => storage_path('api-docs'),
    'annotations'    => [
        [
            'module_lookup'  => base_path('Modules/Crypto/Http/Controllers'),
            'module_include' => [],
            'common_include' => [
                base_path('Modules/Crypto/Http/Requests'),
                base_path('Modules/Crypto/Http/Resources'),
                base_path('Modules/Crypto/Enums'),
            ],
        ],
    ],
];
