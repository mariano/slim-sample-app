<?php
namespace Infrastructure\Queue;

use Disque\Client;
use Disque\Queue\Job;
use Disque\Queue\JobNotAvailableException;
use InvalidArgumentException;
use Queue\JobInterface;
use Queue\QueueInterface;

class Queue implements QueueInterface
{
    private $queueName;
    private $client;
    private $getTimeout = 1000;

    public function __construct($queueName, array $servers)
    {
        if (empty($servers)) {
            throw new InvalidArgumentException('No servers specified');
        }

        $this->queueName = $queueName;
        $this->client = new Client($servers);
    }

    public function add(JobInterface $job)
    {
        $disqueJob = new Job();
        $disqueJob->setBody($job->getBody());
        $this->client->queue($this->queueName)->push($disqueJob);
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