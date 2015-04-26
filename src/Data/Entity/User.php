<?php
namespace Data\Entity;

use DateTime;
use InvalidArgumentException;
use Data\Entity\UserInterface;

class User implements UserInterface
{
    const PASSWORD_ALGORITHM = PASSWORD_BCRYPT;

    protected $id;

    protected $email;

    protected $firstName;

    protected $lastName;

    protected $password = null;

    protected $country;

    protected $created;

    public function __construct()
    {
        $this->created = new DateTime();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        if (trim($email) === '') {
            throw new InvalidArgumentException("Email cannot be empty");
        }
        $this->email = $email;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($firstName)
    {
        if (trim($firstName) === '') {
            throw new InvalidArgumentException("First name cannot be empty");
        }
        $this->firstName = $firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName($lastName)
    {
        if (trim($lastName) === '') {
            throw new InvalidArgumentException("Last name cannot be empty");
        }
        $this->lastName = $lastName;
    }

    public function getName()
    {
        $firstName = $this->getFirstName();
        $lastName = $this->getLastName();

        $name = $firstName;
        if (!empty($firstName) && !empty($lastName)) {
            $name .= " ";
        }
        $name .= $lastName;
        return $name;
    }

    final public function isPasswordSet()
    {
        return !is_null($this->password);
    }

    final public function setPassword($password = null)
    {
        if (!is_null($password)) {
            $password = base64_encode(hash('sha256', $password, true));
            $this->password = password_hash($password, static::PASSWORD_ALGORITHM);
        } else {
            $this->password = $password;
        }
    }

    final public function checkPassword($password)
    {
        if (!$this->isPasswordSet()) {
            return false;
        }
        $password = base64_encode(hash('sha256', $password, true));
        return password_verify($password, $this->password);
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setCountry($country)
    {
        if (trim($country) === '') {
            throw new InvalidArgumentException("Country cannot be empty");
        } elseif (strlen($country) !== 2) {
            throw new InvalidArgumentException("Invalid country code specified: {$country}");
        }
        $this->country = $country;
    }

    public function getCreated()
    {
        return $this->created;
    }
}