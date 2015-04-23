<?php
namespace Data\Store\Repository;

interface UserRepositoryInterface
{
    /**
     * Get User by email
     *
     * @param string $email Email
     * @return User|null
     */
    public function findOneByEmail($email);
}