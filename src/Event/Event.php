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
        $this->setName($name);
        $this->setArguments($arguments);
    }

    /**
     * Get the event name
     *
     * @return string Event name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the event name
     *
     * @param string $name Event name
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
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

    /**
     * Set args property.
     *
     * @param array $args Arguments
     * @return void
     */
    public function setArguments(array $arguments = [])
    {
        $this->arguments = $arguments;
    }
}