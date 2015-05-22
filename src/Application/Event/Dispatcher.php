<?php
namespace Application\Event;

use Application\Queue\EventQueueInterface;
use Domain\Event\DispatcherInterface;
use Domain\Event\EventInterface;

class Dispatcher implements DispatcherInterface
{
    /**
     * Queue
     *
     * @var EventQueueInterface
     */
    private $queue;

    /**
     * Create instance
     *
     * @param EventQueueInterface $queue Queue
     */
    public function __construct(EventQueueInterface $queue)
    {
        $this->queue = $queue;
    }

    /**
     * Dispatch an event
     *
     * @param string $name Event name
     * @param EventInterface $event Event to dispatch
     */
    public function dispatch($name, EventInterface $event)
    {
        $this->queue->add($name, $event);
    }
}