<?php
namespace Infrastructure\Queue;

use Disque\Queue\Job;
use Disque\Queue\JobInterface;
use Event\Event;
use Event\EventInterface;

class EventJob extends Job implements JobInterface
{
    /**
     * Underlying event
     *
     * @var EventInterface
     */
    private $event;

    /**
     * Build a job with the given body
     *
     * @param EventInterface $event Event
     */
    public function __construct(EventInterface $event)
    {
        $this->event = $event;
        $this->setBody([
            'name' => $event->getName(),
            'arguments' => $event->getArguments()
        ]);
    }

    /**
     * Get underlying event
     *
     * @return EventInterface
     */
    public function getEvent()
    {
        if (!isset($this->event) && !empty($this->body)) {
            $this->event = new Event($this->body['name'], $this->body['arguments']);
        }
        return $this->event;
    }
}