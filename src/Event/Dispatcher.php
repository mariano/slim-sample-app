<?php
namespace Event;

use Queue\EventQueueInterface;

class Dispatcher
{
    /**
     * Queue
     *
     * @var EventQueueInterface
     */
    private $queue;

    public function __construct(EventQueueInterface $queue)
    {
        $this->queue = $queue;
    }

    /**
     * Dispatch an event
     *
     * @param EventInterface $event Event to dispatch
     */
    public function dispatch(EventInterface $event)
    {
        $this->queue->add($event);
    }
}