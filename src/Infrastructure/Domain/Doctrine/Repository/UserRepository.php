<?php
namespace Infrastructure\Domain\Doctrine\Repository;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Domain\Store\Repository\UserRepositoryInterface;
use Infrastructure\Domain\Doctrine\Entity\SocialAccount;
use Infrastructure\Domain\Doctrine\Entity\User;

class UserRepository implements UserRepositoryInterface
{
    /**
     * Doctrine entity manager
     *
     * @var Doctrine\ORM\EntityManagerInterface
     */
    private $em;

    /**
     * Doctrine repository
     *
     * @var Doctrine\Common\Persistence\ObjectRepository
     */
    private $repo;

    /**
     * Create instance
     *
     * @param Doctrine\ORM\EntityManagerInterface EntityManager
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(User::class);
    }

    /**
     * Get User by email
     *
     * @param string $email Email
     * @return User|null
     */
    public function findOneByEmail($email)
    {
        return $this->repo->findOneBy(['email' => $email]);
    }

    /**
     * Get the User linked to a SocialAccount
     *
     * @param string $type SocialAccountIdentifier $type
     * @param string $identifier Identifier
     * @return User|null
     */
    public function findOneBySocialAccount($type, $identifier)
    {
        $socialAccount = $this->em->getRepository(SocialAccount::class)
            ->findOneBy(compact('type', 'identifier'));
        return (isset($socialAccount) ? $socialAccount->getUser() : null);
    }
}