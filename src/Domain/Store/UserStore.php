<?php
namespace Domain\Store;

use InvalidArgumentException;
use Domain\Store\Exception\InvalidLoginException;
use Domain\Store\Repository\UserRepositoryInterface;

class UserStore implements UserStoreInterface
{
    /**
     * Repository
     *
     * @var UserRepositoryInterface
     */
    private $repo;

    /**
     * Constructor
     *
     * @param UserRepositoryInterface Repository
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
     * @throws InvalidLoginException
     */
    public function getByLogin($email, $password)
    {
        $user = $this->repo->findOneByEmail($email);
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

        $user = $this->repo->findOneBySocialAccount($type, $data['identifier']);
        if (isset($user)) {
            return $user;
        }

        return $this->repo->addFromSocialAccount($type, $data);
    }
}