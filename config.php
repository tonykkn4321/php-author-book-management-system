<?php
$env = getenv('RAILWAY_ENVIRONMENT_NAME') ?: 'development';

$databaseUrl = getenv('DATABASE_URL');
$productionConfig = [];

if ($databaseUrl) {
    $url = parse_url($databaseUrl);
    $productionConfig = [
        'dsn' => sprintf(
            'pgsql:host=%s;port=%s;dbname=%s',
            $url['host'],
            $url['port'],
            ltrim($url['path'], '/')
        ),
        'username' => $url['user'],
        'password' => $url['pass'],
    ];
}

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
    'production' => $productionConfig,
];

return $config[$env];
