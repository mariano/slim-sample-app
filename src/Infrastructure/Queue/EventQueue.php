<?php
namespace Infrastructure\Queue;

use Application\Queue\EventQueueInterface;
use Disque\Client;
use Disque\Queue\Job;
use Disque\Queue\JobNotAvailableException;
use Domain\Event\EventInterface;
use Domain\Event\UserRegistered;
use Infrastructure\Queue\Event\UserRegisteredEventJob;
use InvalidArgumentException;

class EventQueue implements EventQueueInterface
{
    /**
     * What JobInterface classes handle each EventInterface
     *
     * @var array
     */
    protected $jobs = [
        UserRegistered::class => UserRegisteredEventJob::class
    ];

    /**
     * Queue name
     *
     * @var string
     */
    private $queueName = 'events';

    /**
     * Disque client
     *
     * @var Client
     */
    private $client;

    /**
     * Disque queue
     *
     * @var Queue
     */
    private $queue;

    /**
     * How many milliseconds to wait for a job per loop
     *
     * @var int
     */
    private $getTimeout = 1000;

    /**
     * Create instance
     *
     * @param array $servers Disque servers
     */
    public function __construct(array $servers)
    {
        if (empty($servers)) {
            throw new InvalidArgumentException('No servers specified');
        }

        $this->client = new Client($servers);
        $this->queue = $this->client->queue($this->queueName);
        $this->queue->setMarshaler(new EventJobMarshaler());
    }

    /**
     * Add an event to the queue
     *
     * @param string $name Event name
     * @param EventInterface $event Event
     * @return void
     */
    public function add($name, EventInterface $event)
    {
        $eventClass = get_class($event);
        if (!isset($this->jobs[$eventClass])) {
            throw new InvalidArgumentException("Don't know what job to use for event {$eventClass}");
        }
        $class = $this->jobs[$eventClass];
        $job = $class::getInstance($name, $event);
        $this->client->queue($this->queueName)->push($job);
    }

    /**
     * Get an event job from the queue
     *
     * @return EventJob
     */
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

    /**
     * Get queue name
     *
     * @return string Queue name
     */
    public function getName()
    {
        return $this->queueName;
    }

    /**
     * Marks the given job as processed
     *
     * @param mixed $job Job
     * @return void
     */
    public function processed($job)
    {
        $this->client->queue($this->queueName)->processed($job);
    }
}