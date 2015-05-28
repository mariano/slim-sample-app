<?php
namespace Domain\Repository;

interface UserRepositoryInterface
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

    /**
     * Get/Create a User out of a SocialAccountInterface
     *
     * @param string $type SocialAccountInterface type
     * @param array $data SocialAccount data (should at least contain identifier)
     * @return User
     */
    public function getBySocialAccount($type, array $data);
}