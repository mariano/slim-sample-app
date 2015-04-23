<?php
use Aura\Di\Container;
use Aura\Di\Factory;

$di = new Container(new Factory());
$di->setAutoResolve(false);

// Settings

$di->set('settings', $di->lazyRequire(ROOT_APP . '/config.php'));

// Data stores

foreach ([
    'UserStore' => Data\Store\UserStore::class
] as $storeInterface => $storeImplementation) {
    $di->set($storeInterface, $di->lazyNew($storeImplementation));
    $di->params[$storeImplementation]['repo'] = $di->lazyGet('UserRepository');
}

// Data repositories

foreach ([
    'UserRepository' => Infrastructure\Data\Doctrine\Repository\UserRepository::class
] as $repositoryInterface => $repositoryImplementation) {
    $di->params[$repositoryImplementation]['em'] = $di->lazyGet('EntityManager');
    $di->set($repositoryInterface, $di->lazyNew($repositoryImplementation));
}

// Doctrine

$di->set('EntityManager', $di->lazy(function () use ($di) {
    $settings = $di->get('settings');
    $createEntityManager = require_once(ROOT_APP . '/Infrastructure/Data/Doctrine/bootstrap.php');
    return $createEntityManager($settings['db']);
}));

// Controllers

$di->setter[Controller\ControllerInterface::class]['setRenderer'] = $di->lazyGet('ViewRenderer');
$di->setter[Controller\ControllerInterface::class]['setSettings'] = $di->lazyGet('settings');

$di->params[Controller\Auth::class]['store'] = $di->lazyGet('UserStore');

// View

$di->set('ViewRenderer', $di->lazy(function () use($di) {
    $settings = $di->get('settings');
    $app = $di->get(Slim\App::class);
    $view = new View\Twig();
    $view->parserOptions = $settings['view'];
    $view->twigTemplateDirs = $settings['view']['templates'];
    $view->parserExtensions = [
        new View\Twig\Extension($app['request']->getUri(), $app['router'])
    ];
    return $view;
}));

return $di;