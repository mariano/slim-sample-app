<?php
namespace Event;

interface EventInterface
{
    /**
     * Get event name
     *
     * @return string
     */
    public function getName();

    /**
     * Getter for all arguments.
     *
     * @return array
     */
    public function getArguments();
}