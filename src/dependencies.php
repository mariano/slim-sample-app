<?php
use Aura\Di\Container;
use Aura\Di\Factory;
use Controller\ControllerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Slim\App;
use View\RendererInterface;
use View\Twig;

$di = new Container(new Factory());
$di->set('settings', $di->lazyRequire(ROOT_APP . '/config.php'));
$di->set(RendererInterface::class, $di->lazy(function () use($di) {
    $settings = $di->get('settings');
    $app = $di->get(App::class);
    $view = new Twig();
    $view->parserOptions = $settings['view'];
    $view->twigTemplateDirs = $settings['view']['templates'];
    $view->parserExtensions = [
        new Twig\Extension($app['request']->getUri(), $app['router'])
    ];
    return $view;
}));
$di->set(EntityManagerInterface::class, $di->lazy(function () use ($di) {
    $settings = $di->get('settings');
    $createEntityManager = require_once(ROOT_APP . '/Infrastructure/Data/Doctrine/bootstrap.php');
    return $createEntityManager($settings['db']);
}));
$di->setter[ControllerInterface::class]['setRenderer'] = $di->lazyGet(RendererInterface::class);
$di->setter[ControllerInterface::class]['setSettings'] = $di->lazyGet('settings');

return $di;