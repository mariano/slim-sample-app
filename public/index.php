<?php
use Slim\App;

define ('ROOT', dirname(__DIR__));
define ('ROOT_APP', ROOT . '/src');

require_once(ROOT . '/vendor/autoload.php');

// Set up dependencies

$di = require_once(ROOT_APP . '/dependencies.php');

// Create application

$app = new App($di->get('settings'));
$di->set(App::class, $app);

// Set up controllers

$app['Auth'] = function (App $app) use ($di) {
    return $di->newInstance(Controller\Auth::class, [
        'store' => $di->get('UserStore')
    ]);
};

$app['Hello'] = function (App $app) use ($di) {
    return $di->newInstance(Controller\Hello::class);
};

// Set up routes

$app->get('/hello/{name}', 'Hello:hello')->setName('hello');
$app->get('/login', 'Auth:login')->setName('login');
$app->post('/login', 'Auth:doLogin');

// Run application

$app->run();