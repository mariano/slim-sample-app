<?php
namespace Event;

use InvalidArgumentException;

class UserRegistered extends BaseEvent implements EventInterface
{
    const EVENT_NAME = 'user:registered';
    private $email;

    /**
     * Create an instance of the event out of the given arguments
     *
     * @param string $name Event name
     * @param array $arguments Arguments
     * @return EventInterface an instance
     */
    static function getInstance($name, array $arguments)
    {
        if (!isset($arguments['email'])) {
            throw new InvalidArgumentException('Missing email');
        }
        $event = new static($arguments['email']);
        $event->setName($name);
        return $event;
    }

    public function __construct($email)
    {
        $this->email = $email;
        $this->setName(self::EVENT_NAME);
        $this->setArguments(compact('email'));
    }
}