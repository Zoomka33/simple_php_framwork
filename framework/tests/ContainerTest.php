<?php

declare(strict_types=1);

namespace Kalinin\Framework\Tests;

use Kalinin\Framework\Container\Container;
use Kalinin\Framework\Container\Exceptions\ContainerException;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    public function test_getting_service_from_container()
    {
        $container = new Container();

        $container->add('kalinin-class', KalininClass::class);

        $this->assertInstanceOf(KalininClass::class, $container->get('kalinin-class'));
    }

    public function test_container_throw_exception_ContainerException_if_add_wrong_class()
    {
        $container = new Container();

        $this->expectException(ContainerException::class);
        $container->add('wrong-class');

    }

    public function test_has_method()
    {
        $container = new Container();

        $container->add('kalinin-class', KalininClass::class);

        $this->assertTrue($container->has('kalinin-class'));
        $this->assertFalse($container->has('worng-class'));
    }

    public function test_recursively_autowried()
    {
        $container = new Container();

        $container->add('kalinin-class', KalininClass::class);

        /**
         * @var KalininClass $kClass
         */
        $kClass = $container->get('kalinin-class');
        $testClass = $kClass->getTestClass();

        $this->assertInstanceOf(TestClass::class, $kClass->getTestClass());
        $this->assertInstanceOf(VKClass::class, $testClass->getVKClass());
        $this->assertInstanceOf(TelegramClass::class, $testClass->getTelegramClass());
    }
}
