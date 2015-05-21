<?php
namespace Infrastructure\Queue;

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

        $job = new EventJob();
        $job->setBody($body);
        return $job;
    }

    public function marshal(JobInterface $job)
    {
        if (!($job instanceof EventJob)) {
            throw new MarshalException('Not an Event job');
        }

        return json_encode($job->getBody());
    }
}