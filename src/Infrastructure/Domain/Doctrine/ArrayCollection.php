<?php
namespace Infrastructure\Domain\Doctrine;

use Domain\CollectionInterface as BaseCollectionInterface;
use Doctrine\Common\Collections\Collection as DoctrineCollection;
use Doctrine\Common\Collections\ArrayCollection as DoctrineArrayCollection;

class ArrayCollection extends DoctrineArrayCollection implements CollectionInterface, DoctrineCollection
{
}