<?php
namespace Domain\Store\Repository;

interface UserRepositoryInterface
{
    /**
     * Get User by email
     *
     * @param string $email Email
     * @return User|null
     */
    public function findOneByEmail($email);

    /**
     * Get the User linked to a SocialAccount
     *
     * @param string $type SocialAccountIdentifier type
     * @param string $identifier Identifier
     * @return User|null
     */
    public function findOneBySocialAccount($type, $identifier);

    /**
     * Add a new user with data from a SocialAccount
     *
     * @param string $type SocialAccountIdentifier type
     * @param array $data Data
     * @return User
     */
    public function addFromSocialAccount($type, array $data);
}