<?php
namespace Infrastructure\Data\Doctrine;

use Data\CollectionInterface as BaseCollectionInterface;
use Doctrine\Common\Collections\Collection as DoctrineCollection;

interface CollectionInterface extends DoctrineCollection, BaseCollectionInterface
{
}