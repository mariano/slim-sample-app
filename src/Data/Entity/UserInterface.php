<?php
namespace Data\Entity;

interface UserInterface
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