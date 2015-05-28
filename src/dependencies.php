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
    $createEntityManager = require_once(ROOT_APP . '/Infrastructure/Domain/Doctrine/bootstrap.php');
    return $createEntityManager($settings['db']);
}));

foreach ([
    'User' => Infrastructure\Domain\Doctrine\Repository\UserRepository::class
] as $entity => $repositoryImplementation) {
    $di->set("{$entity}Repository", $di->lazyNew($repositoryImplementation, [
        'em' => $di->lazyGet('EntityManager')
    ]));
}

// Controllers

$di->setter[Application\Controller\ControllerInterface::class]['setRenderer'] = $di->lazyGet('ViewRenderer');
$di->setter[Application\Controller\ControllerInterface::class]['setSettings'] = $di->lazyGet('settings');

// View

$di->set('ViewRenderer', $di->lazy(function () use ($di) {
    $settings = $di->get('settings');
    $view = new Application\View\Twig($settings['view']['templates'], $settings['view']);
    $di->get('container')->register($view);
    return $view;
}));

// Job queue

$di->set('queue:events', $di->lazy(function () use ($di) {
    $settings = $di->get('settings');
    $settings['job'] += [
        'servers' => ''
    ];
    $queue = new Infrastructure\Queue\EventQueue(array_map('trim', explode(',', $settings['job']['servers'])));
    return $queue;
}));

// Events

$di->set('event', $di->lazy(function () use ($di) {
    $queue = $di->get('queue:events');
    return new Application\Event\Dispatcher($queue);
}));

return $di;