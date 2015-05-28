<?php
use Slim\App;

$di = require_once(__DIR__ . '/../src/bootstrap.php');

// Create application

$app = new App($di->get('settings'));
$container = $app->getContainer();
$di->set('container', $container);

// Social integration

$di->set('HybridAuth', $di->lazy(function () use ($di, $app) {
    $container = $app->getContainer();
    $uri = $container['request']->getUri();
    $endpointUri = new \Slim\Http\Uri(
        $uri->getScheme(),
        $uri->getHost(),
        $uri->getPort(),
        $container['router']->urlFor('loginSocialEndpoint')
    );
    $endpointUri = $endpointUri->withBasePath(ltrim($uri->getBasePath(), '/'));

    $settings = $di->get('settings');
    try {
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
                    'approve_prompt' => 'force'
                ]
            ]
        ]);
    } catch (\Exception $e) {
        // We could be here because of a User rejecting permission
        $app->stop($container['response']->withRedirect($container['router']->urlFor('login'), 307));
        return null;
    }
    return $hybridAuth;
}));

// Set up controllers

$container['Auth'] = function () use ($di) {
    return $di->newInstance(Application\Controller\Auth::class, [
        'repository' => $di->get('UserRepository'),
        'hybridAuth' => $di->get('HybridAuth')
    ]);
};

$container['Hello'] = function () use ($di) {
    /*
    $di->get('event')->dispatch('user:registered', new Domain\Event\UserRegistered('john@example.com'));
    echo 'DONE';
    exit;
    $hybridAuth = $di->get('HybridAuth');
    echo 'FACEBOOK:<hr />';
    var_dump($hybridAuth->isConnectedWith('Facebook'));
    $fb = $hybridAuth->authenticate('Facebook');
    var_dump($fb->getUserProfile());
    echo 'GOOGLE:<hr />';
    var_dump($hybridAuth->isConnectedWith('Google'));
    $google = $hybridAuth->authenticate('Google');
    try {
        var_dump($google->getUserProfile());
    } catch(\Exception $e) {
        echo $e->getMessage();
        var_dump($e);
    }
    exit;
    */
    return $di->newInstance(Application\Controller\Hello::class);
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