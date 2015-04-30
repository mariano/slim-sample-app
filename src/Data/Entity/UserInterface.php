<?php
namespace Data\Entity;

use DateTime;

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
    public function getLocale();
    public function setLocale($locale);
    public function setVerified(DateTime $verified);
    public function isVerified();
    public function getCreated();
    public function addSocialAccount(SocialAccountInterface $socialAccount);
}