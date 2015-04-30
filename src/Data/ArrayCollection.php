<?php
namespace Data;

class ArrayCollection implements CollectionInterface
{
    /**
     * An array containing the entries of this collection.
     *
     * @var array
     */
    private $elements = [];

    /**
     * {@inheritDoc}
     */
    public function add($value)
    {
        $this->elements[] = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function isEmpty()
    {
        return empty($this->elements);
    }

    /**
     * {@inheritDoc}
     */
    public function toArray()
    {
        return $this->elements;
    }
}