<?php
namespace Event;

use Queue\QueueInterface;

class Dispatcher
{
    /**
     * Job queue
     *
     * @var QueueInterface
     */
    private $queue;

    public function __construct(QueueInterface $queue)
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
        $this->queue->add(new EventJob($event));
    }
}