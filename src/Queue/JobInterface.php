<?php
namespace Queue;

interface JobInterface
{
    public function getQueue();
    public function setQueue($queue);
    public function setBody(array $args);
    public function getBody();
}