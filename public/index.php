<?php
use Slim\App;

$di = require_once(__DIR__ . '/../src/bootstrap.php');

// Create application

$app = new App($di->get('settings'));
$di->set(App::class, $app);

// Social integration

$di->set('HybridAuth', $di->lazy(function () use ($di) {
    $settings = $di->get('settings');
    $hybridAuth = new Hybrid_Auth([
        'base_url' => 'http://app.slim.loc/login/social/endpoint',
        'providers' => [
            'Facebook' => [
                'enabled' => true,
                'keys' => [
                    'id' => $settings['fb']['id'],
                    'secret' => $settings['fb']['secret']
                ],
                'scope' => implode(',', [
                    'email'
                ]),
                'display' => 'popup'
            ]
        ]
    ]);
    return $hybridAuth;
}));

// Set up controllers

$app['Auth'] = function (App $app) use ($di) {
    return $di->newInstance(Controller\Auth::class, [
        'store' => $di->get('UserStore'),
        'hybridAuth' => $di->get('HybridAuth')
    ]);
};

$app['Hello'] = function (App $app) use ($di) {
    $hybridAuth = $di->get('HybridAuth');
    var_dump($hybridAuth->isConnectedWith('Facebook'));
    $fb = $hybridAuth->authenticate('Facebook');
    var_dump($fb->getUserProfile());
    exit;
    return $di->newInstance(Controller\Hello::class);
};

// Set up routes

$app->get('/hello/{name}', 'Hello:hello')->setName('hello');
$app->get('/login', 'Auth:login')->setName('login');
$app->get('/login/social', 'Auth:loginSocial')->setName('loginSocial');
$app->get('/login/social/endpoint', 'Auth:endpoint');
$app->post('/login', 'Auth:doLogin');

// Run application

$app->run();