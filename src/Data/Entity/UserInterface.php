<?php
namespace Data\Entity;

interface UserInterface
{
    public function getId();
    public function getEmail();
    public function setEmail($email);
    public function getFirstName();
    public function setFirstName($firstName);
    public function getLastName();
    public function setLastName($lastName);
    public function getName();
    public function isPasswordSet();
    public function setPassword($password);
    public function checkPassword($password);
    public function getCountry();
    public function setCountry($country);
    public function getCreated();
}