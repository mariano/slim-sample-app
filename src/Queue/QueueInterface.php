<?php
namespace Queue;

interface QueueInterface
{
    public function add(JobInterface $job);
    public function get();
    public function processed($job);
    public function getName();
}