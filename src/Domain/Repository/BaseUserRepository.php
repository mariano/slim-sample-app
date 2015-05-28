<?php
namespace Domain\Repository;

use InvalidArgumentException;

abstract class BaseUserRepository implements UserRepositoryInterface
{
    /**
     * Check an email and password matches a valid User
     *
     * @param string $email Email
     * @param string $password Password
     * @return User
     * @throws InvalidLoginException
     */
    public function getByLogin($email, $password)
    {
        $user = $this->findOneByEmail($email);
        if (!$user || !$user->checkPassword($password)) {
            throw new InvalidLoginException($email);
        }
        return $user;
    }

    /**
     * Get/Create a User out of a SocialAccountInterface
     *
     * @param string $type SocialAccountInterface type
     * @param array $data SocialAccount data (should at least contain identifier)
     * @return User
     */
    public function getBySocialAccount($type, array $data)
    {
        if (empty($data['identifier'])) {
            throw new InvalidArgumentException('Missing required data from Social account');
        }

        $user = $this->findOneBySocialAccount($type, $data['identifier']);
        if (isset($user)) {
            return $user;
        }

        return $this->addFromSocialAccount($type, $data);
    }

    /**
     * Get User by email
     *
     * @param string $email Email
     * @return User|null
     */
    abstract public function findOneByEmail($email);

    /**
     * Get the User linked to a SocialAccount
     *
     * @param string $type SocialAccountIdentifier type
     * @param string $identifier Identifier
     * @return User|null
     */
    abstract public function findOneBySocialAccount($type, $identifier);

    /**
     * Add a new user with data from a SocialAccount
     *
     * @param string $type SocialAccountIdentifier type
     * @param array $data Data
     * @return User
     */
    abstract public function addFromSocialAccount($type, array $data);
}