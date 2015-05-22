<?php
namespace Infrastructure\Domain\Doctrine;

use Domain\CollectionInterface as BaseCollectionInterface;
use Doctrine\Common\Collections\Collection as DoctrineCollection;

interface CollectionInterface extends DoctrineCollection, BaseCollectionInterface
{
}