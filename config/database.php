<?php

return [

    'connections' => [
        'pgsql1' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST'),
            'port' => env('DB_PORT'),
            'database' => env('DB_DATABASE'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ],
        'pgsql2' => [
            'driver' => 'pgsql',
            'host' => env('DB2_HOST'),
            'database' => env('DB2_DATABASE'),
            'username' => env('DB2_USERNAME'),
            'password' => env('DB2_PASSWORD'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix' => '',
        ],
    ]
];
