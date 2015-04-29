<?php
use Slim\App;

$di = require_once(__DIR__ . '/../src/bootstrap.php');

// Create application

$app = new App($di->get('settings'));
$di->set(App::class, $app);

// Social integration

$di->set('HybridAuth', $di->lazy(function () use ($di, $app) {
    $uri = $app['request']->getUri();
    $endpointUri = $uri->withBasePath(ltrim($uri->getBasePath(), '/'))
        ->withPath($app['router']->urlFor('loginSocialEndpoint'))
        ->withQuery('')
        ->withFragment('');
    $settings = $di->get('settings');
    $hybridAuth = new Hybrid_Auth([
        'base_url' => (string) $endpointUri,
        'providers' => [
            'Facebook' => [
                'enabled' => true,
                'keys' => [
                    'id' => $settings['facebook']['id'],
                    'secret' => $settings['facebook']['secret']
                ],
                'scope' => implode(',', [
                    'email',
                    'public_profile',
                    'user_hometown'
                ]),
                'display' => 'page'
            ],
            'Google' => [
                'enabled' => true,
                'keys' => [
                    'id' => $settings['google']['id'],
                    'secret' => $settings['google']['secret']
                ],
                'scope' => implode(' ', [
                    'https://www.googleapis.com/auth/userinfo.email',
                    'https://www.googleapis.com/auth/userinfo.profile'
                ]),
                'access_type' => 'online',
                'approve_prompt' => 'auto'
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
    echo 'FACEBOOK:<hr />';
    var_dump($hybridAuth->isConnectedWith('Facebook'));
    $fb = $hybridAuth->authenticate('Facebook');
    var_dump($fb->getUserProfile());
    echo 'GOOGLE:<hr />';
    var_dump($hybridAuth->isConnectedWith('Google'));
    $google = $hybridAuth->authenticate('Google');
    var_dump($google->getUserProfile());
    exit;
    return $di->newInstance(Controller\Hello::class);
};

// Set up routes

$app->get('/hello/{name}', 'Hello:hello')->setName('hello');
$app->get('/login', 'Auth:login')->setName('login');
$app->get('/login/social/endpoint', 'Auth:endpoint')->setName('loginSocialEndpoint');
$app->get('/login/{provider}', 'Auth:loginSocial')->setName('loginSocial');
$app->get('/logout', 'Auth:logout');
$app->post('/login', 'Auth:doLogin');

// Run application

$app->run();