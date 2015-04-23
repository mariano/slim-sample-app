<?php
namespace Data\Store;

use Data\Store\Repository\UserRepositoryInterface;

class UserStore implements UserStoreInterface
{
    /**
     * Repository
     *
     * @var Data\Store\Repository\UserStoreInterface
     */
    private $repo;

    /**
     * Constructor
     *
     * @param Data\Store\Repository\UserRepositoryInterface Repository
     */
    public function __construct(UserRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Check an email and password matches a valid User
     *
     * @param string $email Email
     * @param string $password Password
     * @return User
     * @throws Data\Store\Exception\InvalidLoginException
     */
    public function getByLogin($email, $password)
    {
        $user = $this->repo->findOneByEmail($email);
        if (!$user || !$user->checkPassword($password)) {
            throw new InvalidLoginException($email);
        }
        return $user;
    }
}