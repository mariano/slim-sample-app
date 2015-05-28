<?php
namespace Test\Domain\Store;

use InvalidArgumentException;
use Mockery as m;
use PHPUnit_Framework_TestCase;
use Domain\Entity\UserInterface;
use Domain\Entity\User;
use Domain\Store\Exception\InvalidLoginException;
use Domain\Store\Repository\UserRepositoryInterface;
use Domain\Store\UserStoreInterface;
use Domain\Store\UserStore;

class UserStoreTest extends PHPUnit_Framework_TestCase
{
    public function testImplements()
    {
        $store = new UserStore(m::mock(UserRepositoryInterface::class));
        $this->assertInstanceOf(UserStoreInterface::class, $store);
    }

    public function testGetByLoginCallsRepository()
    {
        $this->setExpectedException(InvalidLoginException::class);
        $repo = m::mock(UserRepositoryInterface::class)
            ->shouldReceive('findOneByEmail')
            ->once()
            ->with('email')
            ->andReturn(null)
            ->getMock();
        $store = new UserStore($repo);
        $store->getByLogin('email', 'password');
    }

    public function testGetByLoginChecksPassword()
    {
        $user = m::mock(UserInterface::class)
            ->shouldReceive('checkPassword')
            ->once()
            ->with('password')
            ->andReturn(true)
            ->getMock();
        $repo = m::mock(UserRepositoryInterface::class)
            ->shouldReceive('findOneByEmail')
            ->once()
            ->with('email')
            ->andReturn($user)
            ->getMock();
        $store = new UserStore($repo);
        $store->getByLogin('email', 'password');
    }

    public function testGetByLoginThrowsInvalidLoginWhenNoUser()
    {
        $this->setExpectedException(InvalidLoginException::class);
        $repo = m::mock(UserRepositoryInterface::class)
            ->shouldReceive('findOneByEmail')
            ->once()
            ->with('email')
            ->andReturn(null)
            ->getMock();
        $store = new UserStore($repo);
        $store->getByLogin('email', 'password');
    }

    public function testGetByLoginThrowsInvalidLoginWhenNoPassword()
    {
        $this->setExpectedException(InvalidLoginException::class);
        $user = new User();
        $repo = m::mock(UserRepositoryInterface::class)
            ->shouldReceive('findOneByEmail')
            ->once()
            ->with('email')
            ->andReturn($user)
            ->getMock();
        $store = new UserStore($repo);
        $store->getByLogin('email', 'password');
    }

    public function testGetByLoginThrowsInvalidLoginWhenInvalidPassword()
    {
        $this->setExpectedException(InvalidLoginException::class);
        $user = new User();
        $user->setPassword('another');
        $repo = m::mock(UserRepositoryInterface::class)
            ->shouldReceive('findOneByEmail')
            ->once()
            ->with('email')
            ->andReturn($user)
            ->getMock();
        $store = new UserStore($repo);
        $store->getByLogin('email', 'password');
    }

    public function testGetByLoginFindsUser()
    {
        $user = new User();
        $user->setPassword('password');
        $repo = m::mock(UserRepositoryInterface::class)
            ->shouldReceive('findOneByEmail')
            ->once()
            ->with('email')
            ->andReturn($user)
            ->getMock();
        $store = new UserStore($repo);
        $result = $store->getByLogin('email', 'password');
        $this->assertSame($user, $result);
    }

    public function testGetBySocialAccountMissingIdentifier()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        $store = new UserStore(m::mock(UserRepositoryInterface::class));
        $store->getBySocialAccount('type', []);
    }

    public function testGetBySocialAccountExistingIdentifier()
    {
        $user = new User();
        $repo = m::mock(UserRepositoryInterface::class)
            ->shouldReceive('findOneBySocialAccount')
            ->once()
            ->with('type', 'id')
            ->andReturn($user)
            ->getMock();
        $store = new UserStore($repo);
        $result = $store->getBySocialAccount('type', ['identifier' => 'id']);
        $this->assertSame($user, $result);
    }

    public function testGetBySocialAccountNonExistingIdentifier()
    {
        $user = new User();
        $repo = m::mock(UserRepositoryInterface::class)
            ->shouldReceive('findOneBySocialAccount')
            ->once()
            ->with('type', 'id')
            ->andReturn(null)
            ->shouldReceive('addFromSocialAccount')
            ->with('type', ['identifier' => 'id'])
            ->andReturn($user)
            ->once()
            ->getMock();
        $store = new UserStore($repo);
        $result = $store->getBySocialAccount('type', ['identifier' => 'id']);
        $this->assertSame($user, $result);
    }
}