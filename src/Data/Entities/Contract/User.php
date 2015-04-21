<?php
namespace Data\Entities\Contract;

interface User
{
    public function getId();
    public function getEmail();
    public function setEmail($email);
    public function isPasswordSet();
    public function setPasswordSet($passwordSet);
    public function setPassword($password);
    public function checkPassword($password);
    public function isPasswordOld();
}