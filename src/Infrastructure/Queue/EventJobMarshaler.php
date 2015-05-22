<?php
namespace Infrastructure\Queue;

use Disque\Queue\JobInterface;
use Disque\Queue\Marshal\MarshalerInterface;
use Disque\Queue\Marshal\MarshalException;

class EventJobMarshaler implements MarshalerInterface
{
    /**
     * Creates a JobInterface instance based on data obtained from queue
     *
     * @param string $source Source data
     * @return JobInterface
     * @throws MarshalException
     */
    public function unmarshal($source)
    {
        $body = @json_decode($source, true);
        $this->shouldHaveRightBody($body);
        $class = $body['class'];
        $job = new $class();
        $job->setBody($body);
        return $job;
    }

    /**
     * Marshals the body of the job ready to be put into the queue
     *
     * @param JobInterface $job Job to put in the queue
     * @return string Source data to be put in the queue
     * @throws MarshalException
     */
    public function marshal(JobInterface $job)
    {
        if (!($job instanceof EventJob)) {
            throw new MarshalException('Not an Event job');
        }
        return json_encode($job->getBody());
    }

    /**
     * Check that the body is right
     *
     * @throws MarshalException
     */
    private function shouldHaveRightBody($body)
    {
        if (is_null($body)) {
            throw new MarshalException("Could not deserialize {$source}");
        } elseif (empty($body['class']) || empty($body['name']) || !isset($body['arguments'])) {
            throw new MarshalException('Invalid EventJob body');
        }
    }
}