<?php
namespace Infrastructure\Queue;

use Disque\Client;
use Disque\Queue\Job;
use Disque\Queue\JobNotAvailableException;
use Event\EventInterface;
use InvalidArgumentException;
use Queue\EventQueueInterface;

class EventQueue implements EventQueueInterface
{
    private $queueName = 'events';
    private $client;
    private $queue;
    private $getTimeout = 1000;

    public function __construct(array $servers)
    {
        if (empty($servers)) {
            throw new InvalidArgumentException('No servers specified');
        }

        $this->client = new Client($servers);
        $this->queue = $this->client->queue($this->queueName);
        $this->queue->setMarshaler(new EventJobMarshaler());
    }

    public function add(EventInterface $event)
    {
        $job = new EventJob($event);
        $this->client->queue($this->queueName)->push($job);
    }

    public function get()
    {
        $clientQueue = $this->client->queue($this->queueName);
        while (!isset($job)) {
            try {
                $job = $clientQueue->pull($this->getTimeout);
            } catch (JobNotAvailableException $e) {
                continue;
            }
        }
        return $job;
    }

    public function getName()
    {
        return $this->queueName;
    }

    public function processed($job)
    {
        $this->client->queue($this->queueName)->processed($job);
    }
}