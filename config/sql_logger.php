<?php

return [
    'general'      => [
        'directory'          => storage_path(env('SQL_LOGGER_DIRECTORY', 'logs/sql')),
        'use_seconds'        => env('SQL_LOGGER_USE_SECONDS', false),
        'console_log_suffix' => env('SQL_LOGGER_CONSOLE_SUFFIX', ''),
        'extension'          => env('SQL_LOGGER_LOG_EXTENSION', '.sql'),
    ],
    'formatting'   => [
        'new_lines_to_spaces' => env('SQL_LOGGER_FORMAT_NEW_LINES_TO_SPACES', false),
        'entry_format'        => env('SQL_LOGGER_FORMAT_ENTRY_FORMAT', "/* [origin]\n   Query [query_nr] - [datetime] [[query_time]] */\n[query]\n[separator]\n"),
    ],
    'all_queries'  => [
        'enabled'      => env('SQL_LOGGER_ALL_QUERIES_ENABLED', false),
        'override_log' => env('SQL_LOGGER_ALL_QUERIES_OVERRIDE', false),
        'pattern'      => env('SQL_LOGGER_ALL_QUERIES_PATTERN', '#.*#i'),
        'file_name'    => env('SQL_LOGGER_ALL_QUERIES_FILE_NAME', '[Y-m-d]-log'),
    ],
    'slow_queries' => [
        'enabled'       => env('SQL_LOGGER_SLOW_QUERIES_ENABLED', false),
        'min_exec_time' => env('SQL_LOGGER_SLOW_QUERIES_MIN_EXEC_TIME', 100),
        'pattern'       => env('SQL_LOGGER_SLOW_QUERIES_PATTERN', '#.*#i'),
        'file_name'     => env('SQL_LOGGER_SLOW_QUERIES_FILE_NAME', '[Y-m-d]-slow-log'),
    ],
];
