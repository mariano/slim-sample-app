<?php
namespace Application\Queue;

use Domain\Event\EventInterface;

interface EventQueueInterface extends QueueInterface
{
    /**
     * Add an event to the queue
     *
     * @param string $name Event name
     * @param EventInterface $event Event
     * @return void
     */
    public function add($name, EventInterface $event);
}