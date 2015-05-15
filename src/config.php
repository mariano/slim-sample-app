<?php
use Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(ROOT);
$dotenv->required(['TIMEZONE'])->notEmpty();
$dotenv->required(['DB_DRIVER', 'DB_HOST', 'DB_USER', 'DB_DATABASE'])->notEmpty();
$dotenv->required(['DB_PASSWORD']);
$dotenv->required(['FACEBOOK_APP_ID', 'FACEBOOK_APP_SECRET'])->notEmpty();
$dotenv->required(['GOOGLE_APP_ID', 'GOOGLE_APP_SECRET'])->notEmpty();
$dotenv->required(['JOB_SERVERS'])->notEmpty();

date_default_timezone_set($dotenv->get('TIMEZONE'));

return [
    'db' => [
        'driver' => $dotenv->get('DB_DRIVER'),
        'host' => $dotenv->get('DB_HOST'),
        'user' => $dotenv->get('DB_USER'),
        'password' => $dotenv->get('DB_PASSWORD'),
        'database' => $dotenv->get('DB_DATABASE'),
        'debug' => ($dotenv->get('DB_DEBUG') ? true : false)
    ],
    'view' => [
        'debug' => ($dotenv->get('VIEW_DEBUG') ? true : false),
        'cache' => ROOT . '/cache',
        'templates' => ROOT . '/templates'
    ],
    'facebook' => [
        'id' => $dotenv->get('FACEBOOK_APP_ID'),
        'secret' => $dotenv->get('FACEBOOK_APP_SECRET')
    ],
    'google' => [
        'id' => $dotenv->get('GOOGLE_APP_ID'),
        'secret' => $dotenv->get('GOOGLE_APP_SECRET')
    ],
    'job' => [
        'servers' => $dotenv->get('JOB_SERVERS')
    ]
];