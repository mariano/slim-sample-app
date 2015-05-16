<?php
namespace Queue;

interface QueueInterface
{
    public function add(JobInterface $job);

    public function get($queue);
}