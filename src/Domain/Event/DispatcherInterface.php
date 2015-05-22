<?php
namespace Domain\Event;

interface DispatcherInterface
{
    /**
     * Dispatch an event
     *
     * @param string $name Event name
     * @param EventInterface $event Event to dispatch
     */
    public function dispatch($name, EventInterface $event);
}