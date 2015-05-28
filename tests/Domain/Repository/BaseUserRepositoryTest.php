<?php
namespace Test\Domain\Repository;

use InvalidArgumentException;
use Mockery as m;
use PHPUnit_Framework_TestCase;
use Domain\Entity\UserInterface;
use Domain\Entity\User;
use Domain\Repository\BaseUserRepository;
use Domain\Repository\InvalidLoginException;
use Domain\Repository\UserRepositoryInterface;
use Test\BaseTest;

class UserRepository extends BaseUserRepository
{
    public function findOneByEmail($email)
    {
    }

    public function findOneBySocialAccount($type, $identifier)
    {
    }

    public function addFromSocialAccount($type, array $data)
    {
    }
}

class BaseUserRepositoryTest extends BaseTest
{
    public function testImplements()
    {
        $repo = m::mock(BaseUserRepository::class);
        $this->assertInstanceOf(UserRepositoryInterface::class, $repo);
    }

    public function testGetByLoginCallsRepository()
    {
        $this->setExpectedException(InvalidLoginException::class);
        $repo = m::mock(BaseUserRepository::class)
            ->makePartial()
            ->shouldReceive('findOneByEmail')
            ->once()
            ->with('email')
            ->andReturn(null)
            ->getMock();
        $repo->getByLogin('email', 'password');
    }

    public function testGetByLoginChecksPassword()
    {
        $user = m::mock(UserInterface::class)
            ->shouldReceive('checkPassword')
            ->once()
            ->with('password')
            ->andReturn(true)
            ->getMock();
        $repo = m::mock(BaseUserRepository::class)
            ->makePartial()
            ->shouldReceive('findOneByEmail')
            ->once()
            ->with('email')
            ->andReturn($user)
            ->getMock();
        $repo->getByLogin('email', 'password');
    }

    public function testGetByLoginThrowsInvalidLoginWhenNoUser()
    {
        $this->setExpectedException(InvalidLoginException::class);
        $repo = m::mock(BaseUserRepository::class)
            ->makePartial()
            ->shouldReceive('findOneByEmail')
            ->once()
            ->with('email')
            ->andReturn(null)
            ->getMock();
        $repo->getByLogin('email', 'password');
    }

    public function testGetByLoginThrowsInvalidLoginWhenNoPassword()
    {
        $this->setExpectedException(InvalidLoginException::class);
        $user = new User();
        $repo = m::mock(BaseUserRepository::class)
            ->makePartial()
            ->shouldReceive('findOneByEmail')
            ->once()
            ->with('email')
            ->andReturn($user)
            ->getMock();
        $repo->getByLogin('email', 'password');
    }

    public function testGetByLoginThrowsInvalidLoginWhenInvalidPassword()
    {
        $this->setExpectedException(InvalidLoginException::class);
        $user = new User();
        $user->setPassword('another');
        $repo = m::mock(BaseUserRepository::class)
            ->makePartial()
            ->shouldReceive('findOneByEmail')
            ->once()
            ->with('email')
            ->andReturn($user)
            ->getMock();
        $repo->getByLogin('email', 'password');
    }

    public function testGetByLoginFindsUser()
    {
        $user = new User();
        $user->setPassword('password');
        $repo = m::mock(BaseUserRepository::class)
            ->makePartial()
            ->shouldReceive('findOneByEmail')
            ->once()
            ->with('email')
            ->andReturn($user)
            ->getMock();
        $result = $repo->getByLogin('email', 'password');
        $this->assertSame($user, $result);
    }

    public function testGetBySocialAccountMissingIdentifier()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        $repo = m::mock(BaseUserRepository::class)
            ->makePartial();
        $repo->getBySocialAccount('type', []);
    }

    public function testGetBySocialAccountExistingIdentifier()
    {
        $user = new User();
        $repo = m::mock(BaseUserRepository::class)
            ->makePartial()
            ->shouldReceive('findOneBySocialAccount')
            ->once()
            ->with('type', 'id')
            ->andReturn($user)
            ->getMock();
        $result = $repo->getBySocialAccount('type', ['identifier' => 'id']);
        $this->assertSame($user, $result);
    }

    public function testGetBySocialAccountNonExistingIdentifier()
    {
        $user = new User();
        $repo = m::mock(BaseUserRepository::class)
            ->makePartial()
            ->shouldReceive('findOneBySocialAccount')
            ->once()
            ->with('type', 'id')
            ->andReturn(null)
            ->shouldReceive('addFromSocialAccount')
            ->with('type', ['identifier' => 'id'])
            ->andReturn($user)
            ->once()
            ->getMock();
        $result = $repo->getBySocialAccount('type', ['identifier' => 'id']);
        $this->assertSame($user, $result);
    }
}