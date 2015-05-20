<?php
namespace Event;

interface EventInterface
{
    /**
     * Gets the event's name.
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