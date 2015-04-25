<?php
define ('ROOT', dirname(__DIR__));
define ('ROOT_APP', ROOT . '/src');

require_once(ROOT . '/vendor/autoload.php');

// Set up dependencies

$di = require_once(ROOT_APP . '/dependencies.php');

return $di;