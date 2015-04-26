<?php
namespace Test\Data\Entity;

use DateTime;
use InvalidArgumentException;
use Mockery as m;
use PHPUnit_Framework_TestCase;
use RandomLib\Factory;
use SecurityLib\Strength;
use Data\Entity\UserInterface;
use Data\Entity\User;

class MockUser extends User
{
    public function getPassword()
    {
        return $this->password;
    }
}

class UserTest extends PHPUnit_Framework_TestCase
{
    public function testImplements()
    {
        $entity = new User();
        $this->assertInstanceOf(UserInterface::class, $entity);
    }

    public function testCreatedIsSet()
    {
        $entity = new User();
        $result = $entity->getCreated();
        $this->assertInstanceOf(DateTime::class, $result);
    }

    public function testCreatedPasswordNotSet()
    {
        $entity = new User();
        $result = $entity->isPasswordSet();
        $this->assertFalse($result);
    }

    public function testCreatedPasswordNoMatch()
    {
        $entity = new User();
        $result = $entity->checkPassword(null);
        $this->assertFalse($result);
    }

    public function testSetPassword()
    {
        $entity = new User();
        $entity->setPassword('password');
        $result = $entity->isPasswordSet();
        $this->assertTrue($result);
    }

    public function testSetPasswordHash()
    {
        $entity = new MockUser();
        $entity->setPassword('password');
        $result = $entity->getPassword();
        $this->assertNotEquals('password', $result);
    }

    public function testSetPasswordHashLong()
    {
        $generator = (new Factory())->getGenerator(new Strength(Strength::MEDIUM));
        $password = $generator->generateString(512);
        $entity = new MockUser();
        $entity->setPassword($password);
        $result = $entity->getPassword();
        $this->assertNotEquals($password, $result);
    }

    public function testSetPasswordNoMatchNull()
    {
        $entity = new User();
        $entity->setPassword('password');
        $result = $entity->checkPassword(null);
        $this->assertFalse($result);
    }

    public function testSetPasswordNoMatchOtherPassword()
    {
        $entity = new User();
        $entity->setPassword('password');
        $result = $entity->checkPassword('password2');
        $this->assertFalse($result);
    }

    public function testSetPasswordMatch()
    {
        $entity = new User();
        $entity->setPassword('password');
        $result = $entity->checkPassword('password');
        $this->assertTrue($result);
    }

    public function testSetPasswordMatchLong()
    {
        $generator = (new Factory())->getGenerator(new Strength(Strength::MEDIUM));
        $password = $generator->generateString(512);
        $entity = new MockUser();
        $entity->setPassword($password);
        $result = $entity->checkPassword($password);
        $this->assertTrue($result);
    }

    public function testSetEmailEmpty()
    {
        $this->setExpectedException(InvalidArgumentException::class, 'Email cannot be empty');
        $entity = new User();
        $entity->setEmail('');
    }

    public function testSetEmailSpaces()
    {
        $this->setExpectedException(InvalidArgumentException::class, 'Email cannot be empty');
        $entity = new User();
        $entity->setEmail('   ');
    }

    public function testSetEmail()
    {
        $entity = new User();
        $entity->setEmail('jane@email.com');
        $result = $entity->getEmail();
        $this->assertSame('jane@email.com', $result);
    }

    public function testSetFirstNameEmpty()
    {
        $this->setExpectedException(InvalidArgumentException::class, 'First name cannot be empty');
        $entity = new User();
        $entity->setFirstName('');
    }

    public function testSetFirstNameSpaces()
    {
        $this->setExpectedException(InvalidArgumentException::class, 'First name cannot be empty');
        $entity = new User();
        $entity->setFirstName('   ');
    }

    public function testSetFirstName()
    {
        $entity = new User();
        $entity->setFirstName('Jane');
        $result = $entity->getFirstName();
        $this->assertSame('Jane', $result);
    }

    public function testSetLastNameEmpty()
    {
        $this->setExpectedException(InvalidArgumentException::class, 'Last name cannot be empty');
        $entity = new User();
        $entity->setLastName('');
    }

    public function testSetLastNameSpaces()
    {
        $this->setExpectedException(InvalidArgumentException::class, 'Last name cannot be empty');
        $entity = new User();
        $entity->setLastName('   ');
    }

    public function testSetLastName()
    {
        $entity = new User();
        $entity->setLastName('Doe');
        $result = $entity->getLastName();
        $this->assertSame('Doe', $result);
    }

    public function testGetNameJustFirst()
    {
        $entity = new User();
        $entity->setFirstName('Jane');
        $result = $entity->getName();
        $this->assertSame('Jane', $result);
    }

    public function testGetNameJustLast()
    {
        $entity = new User();
        $entity->setLastName('Doe');
        $result = $entity->getName();
        $this->assertSame('Doe', $result);
    }

    public function testGetName()
    {
        $entity = new User();
        $entity->setFirstName('Jane');
        $entity->setLastName('Doe');
        $result = $entity->getName();
        $this->assertSame('Jane Doe', $result);
    }

    public function testSetCountryEmpty()
    {
        $this->setExpectedException(InvalidArgumentException::class, 'Country cannot be empty');
        $entity = new User();
        $entity->setCountry('');
    }

    public function testSetCountrySpaces()
    {
        $this->setExpectedException(InvalidArgumentException::class, 'Country cannot be empty');
        $entity = new User();
        $entity->setCountry('   ');
    }

    public function testSetCountryInvalid()
    {
        $this->setExpectedException(InvalidArgumentException::class, 'Invalid country code specified: A');
        $entity = new User();
        $entity->setCountry('A');
    }

    public function testCountry()
    {
        $entity = new User();
        $entity->setCountry('AR');
        $result = $entity->getCountry();
        $this->assertSame('AR', $result);
    }
}