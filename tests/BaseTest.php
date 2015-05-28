<?php
namespace Test;

use Mockery as m;
use PHPUnit_Framework_TestCase;

abstract class BaseTest extends PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }
}