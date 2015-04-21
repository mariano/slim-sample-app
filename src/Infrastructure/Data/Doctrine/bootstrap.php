<?php
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\StaticPHPDriver;
use Doctrine\ORM\Tools\Setup;

return function (array $dbParams, $entitiesPath = null) {
    $debug = !empty($dbParams);
    $dbParams = array_diff_key($dbParams, ['debug'=>null]);

    if (is_null($entitiesPath)) {
        $entitiesPath = __DIR__ . '/Entities';
    }

    $config = Setup::createAnnotationMetadataConfiguration((array) $entitiesPath, $debug);
    $entityManager = EntityManager::create($dbParams, $config);
    $driver = new StaticPHPDriver($entitiesPath);
    $entityManager->getConfiguration()->setMetadataDriverImpl($driver);
    return $entityManager;
};