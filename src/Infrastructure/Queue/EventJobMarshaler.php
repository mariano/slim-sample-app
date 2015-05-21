<?php
namespace Infrastructure\Queue;

use Event\Event;
use Disque\Queue\JobInterface;
use Disque\Queue\Marshal\MarshalerInterface;
use Disque\Queue\Marshal\MarshalException;

class EventJobMarshaler implements MarshalerInterface
{
    public function unmarshal($source)
    {
        $body = @json_decode($source, true);
        if (is_null($body)) {
            throw new MarshalException("Could not deserialize {$source}");
        } elseif (!is_array($body) || empty($body['name']) || !isset($body['arguments'])) {
            throw new MarshalException('Not an Event job');
        }

        $event = new Event($body['name'], $body['arguments']);
        return new EventJob($event);
    }

    public function marshal(JobInterface $job)
    {
        if (!($job instanceof EventJob)) {
            throw new MarshalException('Not an Event job');
        }

        return json_encode($job->getBody());
    }
}