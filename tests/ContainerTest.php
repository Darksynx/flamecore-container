<?php
/**
 * FlameCore Container
 * Copyright (C) 2020 FlameCore Team
 *
 * Permission to use, copy, modify, and/or distribute this software for
 * any purpose with or without fee is hereby granted, provided that the
 * above copyright notice and this permission notice appear in all copies.
 *
 * @package  FlameCore\Container
 * @version  1.0-dev
 * @link     https://www.flamecore.org
 * @license  https://opensource.org/licenses/MIT MIT License
 */

namespace FlameCore\Container\Tests;

use FlameCore\Container\Container;
use FlameCore\Container\Exceptions\NotFoundException;
use FlameCore\Container\Factory\Factory;
use FlameCore\Container\Definition\Definition;
use FlameCore\Container\Exceptions\InvalidDefinitionException;
use FlameCore\Container\Exceptions\WrongTypeException;
use FlameCore\Container\Exceptions\AlreadyInUseException;
use PHPUnit\Framework\TestCase;

/**
 * Test class for Container
 */
class ContainerTest extends TestCase
{
    public function test()
    {
        $container = $this->createContainer();
        $container->set('foo', new TestClass());

        $this->assertTrue($container->has('foo'));
        $this->assertFalse($container->isFactory('foo'));
        $this->assertInstanceOf(TestClass::class, $container->get('foo'));

        $container->remove('foo');
        $this->assertFalse($container->has('foo'));
    }

    /**
     * @dataProvider provideFactories
     */
    public function testWithFactories($factory)
    {
        $container = $this->createContainer();
        $container->set('foo', $factory);

        $this->assertTrue($container->isFactory('foo'));

        $firstCall = $container->get('foo');
        $secondCall = $container->get('foo');

        $this->assertInstanceOf(TestClass::class, $firstCall);
        $this->assertSame($firstCall, $secondCall);
    }

    public function testGetThrowsExceptionWhenEntryNotFound()
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('was not found.');

        $container = $this->createContainer();
        $container->get('foo');
    }

    /**
     * @dataProvider provideBadNames
     */
    public function testGetThrowsExceptionWhenIdentifierInvalid($badName)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('entry identifier must');

        $container = $this->createContainer();
        $container->get($badName);
    }

    public function testGetRaw()
    {
        $container = $this->createPrefilledContainer();

        $this->assertInstanceOf(TestClass::class, $container->getRaw('foo'));
        $this->assertInstanceOf(Factory::class, $container->getRaw('bar'));
        $this->assertInstanceOf(Factory::class, $container->getRaw('baz'));
    }

    public function testGetRawThrowsExceptionWhenEntryNotFound()
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('was not found.');

        $container = $this->createContainer();
        $container->getRaw('foo');
    }

    /**
     * @dataProvider provideBadNames
     */
    public function testGetRawThrowsExceptionWhenIdentifierInvalid($badName)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('entry identifier must');

        $container = $this->createContainer();
        $container->getRaw($badName);
    }

    public function testGetOr()
    {
        $object = new FallbackClass();
        $factory = function () {
            return new FallbackClass();
        };
        $factoryObject = new Factory($factory);

        $container = $this->createPrefilledContainer();

        $this->assertInstanceOf(TestClass::class, $container->getOr('foo', $object));
        $this->assertInstanceOf(TestClass::class, $container->getOr('bar', $object));
        $this->assertInstanceOf(TestClass::class, $container->getOr('baz', $object));
        $this->assertInstanceOf(FallbackClass::class, $container->getOr('undef', $object));
        $this->assertInstanceOf(FallbackClass::class, $container->getOr('undef', $factory));
        $this->assertInstanceOf(FallbackClass::class, $container->getOr('undef', $factoryObject));
    }

    public function testGetOrWithRawMode()
    {
        $object = new FallbackClass();
        $factory = function () {
            return new FallbackClass();
        };
        $factoryObject = new Factory($factory);

        $container = $this->createPrefilledContainer();

        $this->assertInstanceOf(TestClass::class, $container->getOr('foo', $object, true));
        $this->assertInstanceOf(Factory::class, $container->getOr('bar', $object, true));
        $this->assertInstanceOf(Factory::class, $container->getOr('baz', $object, true));
        $this->assertInstanceOf(FallbackClass::class, $container->getOr('undef', $object, true));
        $this->assertInstanceOf(Factory::class, $container->getOr('undef', $factory, true));
        $this->assertInstanceOf(Factory::class, $container->getOr('undef', $factoryObject, true));
    }

    /**
     * @dataProvider provideBadNames
     */
    public function testGetOrThrowsExceptionWhenIdentifierInvalid($badName)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('entry identifier must');

        $container = $this->createContainer();
        $container->getOr($badName, new TestClass());
    }

    /**
     * @dataProvider provideFactories
     */
    public function testMake($factory)
    {
        $container = $this->createContainer();
        $container->set('foo', $factory);

        $firstCall = $container->make('foo');
        $secondCall = $container->make('foo');

        $this->assertInstanceOf(TestClass::class, $firstCall);
        $this->assertEquals($firstCall, $secondCall);
    }

    public function testMakeThrowsExceptionWhenEntryNotFound()
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('was not found.');

        $container = $this->createContainer();
        $container->make('foo');
    }

    public function testMakeThrowsExceptionWhenEntryNotFactory()
    {
        $this->expectException(WrongTypeException::class);
        $this->expectExceptionMessage('is not a factory.');

        $container = $this->createContainer();
        $container->set('foo', new TestClass());
        $container->make('foo');
    }

    /**
     * @dataProvider provideBadNames
     */
    public function testMakeThrowsExceptionWhenIdentifierInvalid($badName)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('entry identifier must');

        $container = $this->createContainer();
        $container->make($badName);
    }

    /**
     * @dataProvider provideBadNames
     */
    public function testSetThrowsExceptionWhenIdentifierInvalid($badName)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('entry identifier must');

        $container = $this->createContainer();
        $container->set($badName, new TestClass());
    }

    public function testSetThrowsExceptionWhenScalarValueGiven()
    {
        $this->expectException(WrongTypeException::class);
        $this->expectExceptionMessage('must be either a factory or an object.');

        $container = $this->createContainer();
        $container->set('foo', true);
    }

    public function testRemoveThrowsExceptionWhenEntryNotFound()
    {
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('was not found.');

        $container = $this->createContainer();
        $container->remove('foo');
    }

    /**
     * @dataProvider provideBadNames
     */
    public function testRemoveThrowsExceptionWhenIdentifierInvalid($badName)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('entry identifier must');

        $container = $this->createContainer();
        $container->remove($badName);
    }

    public function testDefine()
    {
        $container = $this->createContainer();
        $container->define('foo', TestClass::class);
        $container->set('foo', new TestClass());

        $this->assertTrue($container->isDefined('foo'));
        $this->assertInstanceOf(Definition::class, $container->getDefinition('foo'));
        $this->assertEquals(TestClass::class, $container->getDefinition('foo')->getType());
    }

    public function testDefineMultiple()
    {
        $container = $this->createContainer();
        $container->defineMultiple($array = [
            'foo' => TestClass::class,
            'bar' => TestClass::class
        ]);

        foreach ($array as $id => $class) {
            $container->set($id, new TestClass());

            $this->assertTrue($container->isDefined($id));

            $definition = $container->getDefinition($id);
            $this->assertInstanceOf(Definition::class, $definition);
            $this->assertEquals($class, $definition->getType());
        }
    }

    /**
     * @dataProvider provideBadNames
     */
    public function testDefineThrowsExceptionWhenIdentifierInvalid($badName)
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('entry identifier must');

        $container = $this->createContainer();
        $container->define($badName, TestClass::class);
    }

    public function testDefineThrowsExceptionWhenClassNotExists()
    {
        $this->expectException(InvalidDefinitionException::class);
        $this->expectExceptionMessage('non-existing class NonExistingClass as type');

        $container = $this->createContainer();
        $container->define('foo', 'NonExistingClass');
    }

    public function testDefineThrowsExceptionWhenEntryAlreadyInUse()
    {
        $this->expectException(AlreadyInUseException::class);
        $this->expectExceptionMessage('value is already assigned');

        $container = $this->createContainer();
        $container->set('foo', new TestClass());
        $container->define('foo', TestClass::class);
    }

    public function testSetThrowsExceptionWhenValueNotMatchesDefinition()
    {
        $this->expectException(WrongTypeException::class);
        $this->expectExceptionMessage('must be either a factory or an object of class FlameCore\Container\Tests\TestClass.');

        $container = $this->createContainer();
        $container->define('foo', TestClass::class);
        $container->set('foo', new \stdClass());
    }

    public function testSetThrowsExceptionWhenReturnValueOfGivenFactoryDoesNotMatchDefinition()
    {
        $this->expectException(WrongTypeException::class);
        $this->expectExceptionMessage('must be either a factory or an object of class FlameCore\Container\Tests\TestClass');

        $container = $this->createContainer();
        $container->define('foo', TestClass::class);
        $container->set('foo', function (): \stdClass {
            new \stdClass();
        });
    }

    public function provideFactories()
    {
        $factory = function () {
            return new TestClass();
        };

        return [
            [$factory],
            [new Factory($factory)]
        ];
    }

    public function provideBadNames()
    {
        return [
            [null],
            [true],
            [1],
            [[]],
            [new \stdClass()],
            ['']
        ];
    }

    /**
     * @return Container
     */
    protected function createContainer()
    {
        return new Container();
    }

    /**
     * @return Container
     */
    protected function createPrefilledContainer()
    {
        $container = $this->createContainer();
        $container->set('foo', new TestClass());
        $container->set('bar', $factory = function () {
            return new TestClass();
        });
        $container->set('baz', new Factory($factory));

        return $container;
    }
}
