<?php
namespace Data\Entity;

use DateTime;
use InvalidArgumentException;

class SocialAccount implements SocialAccountInterface
{
    protected $user;

    protected $type;

    protected $identifier;

    protected $data;

    protected $created;

    public function __construct()
    {
        $this->created = new DateTime();
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser(UserInterface $user)
    {
        $this->user = $user;
    }

    public function setType($type)
    {
        if (!in_array($type, [
            SocialAccountInterface::TYPE_FACEBOOK,
            SocialAccountInterface::TYPE_GOOGLE
        ])) {
            throw new InvalidArgumentException("Invalid SocialAccount type: {$type}");
        }
        $this->type = $type;
    }

    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    public function setData(array $data)
    {
        $this->data = $data;
    }

    public function getCreated()
    {
        return $this->created;
    }
}