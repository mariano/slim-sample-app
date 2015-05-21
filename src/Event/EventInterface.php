<?php
namespace Event;

interface EventInterface
{
    /**
     * Create an instance of the event out of the given arguments
     *
     * @param string $name Event name
     * @param array $arguments Arguments
     * @return EventInterface an instance
     */
    static function getInstance($name, array $arguments);

    /**
     * Get event name
     *
     * @return string
     */
    public function getName();

    /**
     * Set name
     *
     * @param string $name Event name
     * @return void
     */
    public function setName($name);

    /**
     * Getter for all arguments.
     *
     * @return array
     */
    public function getArguments();

    /**
     * Getter for all arguments.
     *
     * @param array $arguments Arguments
     * @return void
     */
    public function setArguments(array $arguments);
}