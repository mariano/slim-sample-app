<?php
namespace Domain\Entity;

use DateTime;

interface SocialAccountInterface
{
    const TYPE_FACEBOOK = 'facebook';
    const TYPE_GOOGLE = 'google';

    public function getUser();
    public function setUser(UserInterface $user);
    public function setType($type);
    public function setIdentifier($identifier);
    public function setData(array $data);
    public function getCreated();
}