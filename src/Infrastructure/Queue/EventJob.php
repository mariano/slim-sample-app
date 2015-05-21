<?php
namespace Infrastructure\Queue;

use Disque\Queue\Job;
use Disque\Queue\JobInterface;
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
    public function __construct(EventInterface $event = null)
    {
        if (isset($event)) {
            $this->event = $event;
            $this->setBody([
                'class' => get_class($event),
                'name' => $event->getName(),
                'arguments' => $event->getArguments()
            ]);
        }
    }

    /**
     * Get underlying event
     *
     * @return EventInterface
     */
    public function getEvent()
    {
        $body = $this->getBody();
        if (!isset($this->event) && !empty($body)) {
            $class = $body['class'];
            $this->event = $class::getInstance($body['name'], $body['arguments']);
        }
        return $this->event;
    }
}