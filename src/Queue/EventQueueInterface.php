<?php
namespace Queue;

use Event\EventInterface;

interface EventQueueInterface
{
    public function add(EventInterface $job);
    public function get();
    public function processed($job);
    public function getName();
}