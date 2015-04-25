<?php
$di = require_once(__DIR__ . '/src/bootstrap.php');

return Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($di->get('EntityManager'));