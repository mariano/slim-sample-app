<?php
namespace Event;

class Event implements EventInterface
{
    /**
     * Event name
     *
     * @var string
     */
    private $name;

    /**
     * Event arguments
     *
     * @var array
     */
    private $arguments = [];

    /**
     * Create instance
     *
     * @param string $name Event name
     * @param array $args Arguments
     */
    public function __construct($name, array $arguments = [])
    {
        $this->name = $name;
        $this->arguments = $arguments;
    }

    /**
     * Get event name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Getter for all arguments.
     *
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }
}