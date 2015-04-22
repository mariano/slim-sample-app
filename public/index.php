<?php
use Slim\App;
use View\Twig;

define ('ROOT', dirname(__DIR__));
define ('ROOT_APP', ROOT . '/src');

require_once(ROOT . '/vendor/autoload.php');

$settings = require_once(ROOT_APP . '/config.php');
$app = new App($settings);

/*
$createEntityManager = require_once(ROOT_APP . '/Infrastructure/Data/Doctrine/bootstrap.php');
$em = $createEntityManager($app['settings']['db']);
$class = $em->getMetadataFactory()->getMetadataFor('Infrastructure\\Data\\Doctrine\\Entities\\User');
var_dump($class); exit;
*/

$view = new Twig();
$view->parserOptions = [
    'debug' => true,
    'cache' => ROOT . '/cache',
];
$view->twigTemplateDirs = ROOT . '/templates';
$view->parserExtensions = [
    new Twig\Extension($app['request']->getUri(), $app['router'])
];

$app['view'] = $view;

$app->get('/login', function (\Slim\Http\Request $request, \Slim\Http\Response $response, array $args) {
    $response->write($this['view']->render('auth/login.html', [
        'debug' => true
    ]));
    return $response;
});

$app->get('/hello/{name}', 'Hello:hello')->setName('hello');

$app['Hello'] = function(App $app) {
    return new \Controller\Hello($app['view']);
};

$app->run();