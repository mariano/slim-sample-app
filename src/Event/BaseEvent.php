<?php
namespace Event;

abstract class BaseEvent implements EventInterface
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
     * Create an instance of the event out of the given arguments
     *
     * @param string $name Event name
     * @param array $arguments Arguments
     * @return EventInterface an instance
     */
    static function getInstance($name, array $arguments)
    {
        $event = new static();
        $event->setName($name);
        $event->setArguments($arguments);
        return $event;
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
     * Set name
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
     * Getter for all arguments.
     *
     * @param array $arguments Arguments
     * @return void
     */
    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;
    }
}