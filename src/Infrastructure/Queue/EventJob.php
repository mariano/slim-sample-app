<?php
namespace Infrastructure\Queue;

use Disque\Queue\Job;
use Disque\Queue\JobInterface;
use Domain\Event\EventInterface;

abstract class EventJob extends Job implements JobInterface
{
    /**
     * Event name
     *
     * @var string
     */
    private $name;

    /**
     * Underlying event
     *
     * @var EventInterface
     */
    private $event;

    /**
     * Build a job
     *
     * @param string $name Event name
     * @param EventInterface $event Event
     * @return EventJob Job
     */
    public static function getInstance($name, EventInterface $event)
    {
        $job = new static();
        $job->setBody([
            'class' => get_class($this),
            'name' => $name,
            'arguments' => []
        ]);
        return $job;
    }

    /**
     * Get event name
     *
     * @return string Name
     */
    public function getName()
    {
        if (!isset($this->name)) {
            $body = $this->getBody();
            $this->name = $body['name'];
        }
        return $this->name;
    }

    /**
     * Get underlying event
     *
     * @return EventInterface
     */
    public function getEvent()
    {
        if (!isset($this->event)) {
            $body = $this->getBody();
            $this->event = $this->getEventInstance($body['arguments']);
        }
        return $this->event;
    }

    /**
     * Build the underlying event instance out of the given arguments
     *
     * @return EventInterface Event
     */
    abstract protected function getEventInstance(array $arguments);
}