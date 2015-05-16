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
    private $client;
    private $getTimeout = 1000;

    public function __construct(array $servers)
    {
        if (empty($servers)) {
            throw new InvalidArgumentException('No servers specified');
        }
        $this->client = new Client($servers);
    }

    public function add(JobInterface $job)
    {
        $disqueJob = new Job();
        $disqueJob->setBody($job->getBody());
        $this->client->queue($job->getQueue())->push($disqueJob);
    }

    public function get($queue)
    {
        $clientQueue = $this->client->queue($queue);
        while (!isset($job)) {
            try {
                $job = $clientQueue->pull($this->getTimeout);
            } catch (JobNotAvailableException $e) {
                continue;
            }
        }
        return $job;
    }

    public function processed($queue, Job $job)
    {
        $this->client->queue($queue)->processed($job);
    }
}