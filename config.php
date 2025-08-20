<?php
$env = getenv('RAILWAY_ENVIRONMENT_NAME') ?: 'development';

$config = [
    'development' => [
        'dsn' => 'mysql:host=localhost;dbname=author_book_management_system',
        'username' => 'root',
        'password' => 'Aa161616',
    ],
    'test' => [
        'dsn' => 'sqlite:./test.sqlite',
        'username' => '',
        'password' => '',
    ],
    'production' => [
        'dsn' => getenv('DATABASE_URL'),
        'username' => '',
        'password' => '',
    ],
];

return $config[$env];