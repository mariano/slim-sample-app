<?php
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Console\ConsoleRunner;

define ('ROOT', __DIR__);
define ('ROOT_APP', ROOT . '/src');

require_once(ROOT . '/vendor/autoload.php');

// Set up dependencies

$di = require_once(ROOT_APP . '/dependencies.php');

$em = call_user_func($di->types[EntityManagerInterface::class]);

return ConsoleRunner::createHelperSet($em);