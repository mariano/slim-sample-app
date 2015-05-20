<?php
namespace Event;

use Queue\JobInterface;

class EventJob implements JobInterface
{
    /**
     * Job body
     *
     * @var array
     */
    private $body = [];

    /**
     * Build a job with the given body
     *
     * @param EventInterface $event Event
     */
    public function __construct(EventInterface $event)
    {
        $this->body = [
            'name' => $event->getName(),
            'arguments' => $event->getArguments()
        ];
    }

    /**
     * Get job body
     *
     * @return array Job body
     */
    public function getBody()
    {
        return $this->body;
    }
}