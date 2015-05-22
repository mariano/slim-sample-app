<?php
namespace Domain\Store;

interface UserStoreInterface
{
    /**
     * Check an email and password matches a valid User
     *
     * @param string $email Email
     * @param string $password Password
     * @return User
     * @throws Data\Store\Exception\InvalidLoginException
     */
    public function getByLogin($email, $password);
}