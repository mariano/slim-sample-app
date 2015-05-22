<?php
namespace Infrastructure\Queue\Event;

use InvalidArgumentException;
use Disque\Queue\JobInterface;
use Domain\Event\EventInterface;
use Domain\Event\UserRegistered;
use Infrastructure\Queue\EventJob;

class UserRegisteredEventJob extends EventJob implements JobInterface
{
    /**
     * Build a job
     *
     * @param string $name Event name
     * @param EventInterface $event Event
     * @return EventJob Job
     */
    public static function getInstance($name, EventInterface $event)
    {
        if (!($event instanceof UserRegistered)) {
            throw new InvalidArgumentException('Not a UserRegistered event');
        }
        $job = new static();
        $job->setBody([
            'class' => get_called_class(),
            'name' => $name,
            'arguments' => [
                'email' => $event->getEmail()
            ]
        ]);
        return $job;
    }

    /**
     * Get underlying event
     *
     * @param array $arguments Arguments
     * @return UserRegisteredEventJob Event
     */
    protected function getEventInstance(array $arguments)
    {
        if (empty($arguments['email'])) {
            throw new InvalidArgumentException('Invalid UserRegisteredEventJob arguments');
        }

        return new UserRegistered($arguments['email']);
    }
}