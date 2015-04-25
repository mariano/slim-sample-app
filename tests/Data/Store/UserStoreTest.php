<?php
namespace Test\Data\Store;

use Mockery as m;
use PHPUnit_Framework_TestCase;
use Data\Entity\UserInterface;
use Data\Entity\User;
use Data\Store\Exception\InvalidLoginException;
use Data\Store\Repository\UserRepositoryInterface;
use Data\Store\UserStoreInterface;
use Data\Store\UserStore;

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
}