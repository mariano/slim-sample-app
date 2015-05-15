<?php
use Aura\Di\Container;
use Aura\Di\Factory;

$di = new Container(new Factory());
$di->setAutoResolve(false);

// Settings

$di->set('settings', $di->lazyRequire(ROOT_APP . '/config.php'));

// Data

$di->set('EntityManager', $di->lazy(function () use ($di) {
    $settings = $di->get('settings');
    $createEntityManager = require_once(ROOT_APP . '/Infrastructure/Data/Doctrine/bootstrap.php');
    return $createEntityManager($settings['db']);
}));

foreach ([
    'User' => [
        Data\Store\UserStore::class,
        Infrastructure\Data\Doctrine\Repository\UserRepository::class
    ]
] as $entity => $store) {
    list($storeImplementation, $repositoryImplementation) = $store;
    $di->set("{$entity}Store", $di->lazyNew($storeImplementation, [
        'repo' => $di->lazyNew($repositoryImplementation, [
            'em' => $di->lazyGet('EntityManager')
        ])
    ]));
}

// Controllers

$di->setter[Controller\ControllerInterface::class]['setRenderer'] = $di->lazyGet('ViewRenderer');
$di->setter[Controller\ControllerInterface::class]['setSettings'] = $di->lazyGet('settings');

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

// Job queue

$di->set('queue', $di->lazy(function () use($di) {
    $settings = $di->get('settings');
    $settings['job'] += [
        'servers' => ''
    ];
    $servers = array_map('trim', explode(',', $settings['job']['servers']));
    if (empty($servers)) {
        throw new InvalidArgumentException('No servers specified');
    }
    return new Disque\Client($servers);
}));

return $di;