<?php
namespace Data\Entities;

use Data\Entities\Contract\UserInterface;

class User implements UserInterface
{
    const PASSWORD_ALGORITHM = PASSWORD_BCRYPT;

    protected $id;

    protected $email;

    protected $password;

    protected $passwordSet = false;

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
        $this->email = $email;
    }

    public function isPasswordSet()
    {
        return $this->passwordSet;
    }

    public function setPasswordSet($passwordSet)
    {
        $this->passwordSet = !empty($passwordSet);
    }

    public function setPassword($password)
    {
        $this->password = password_hash($password, static::PASSWORD_ALGORITHM);
        $this->passwordSet = true;
    }

    public function checkPassword($password)
    {
        return ($this->isPasswordSet() && password_verify($password, $this->password));
    }

    public function isPasswordOld()
    {
        return ($this->isPasswordSet() && password_needs_rehash($this->getPassword(), static::PASSWORD_ALGORITHM));
    }
}