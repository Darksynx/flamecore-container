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
 * @version  1.0
 * @link     https://www.flamecore.org
 * @license  https://opensource.org/licenses/MIT MIT License
 */

namespace FlameCore\Container\Tests;

use FlameCore\Container\Factory\Factory;
use PHPUnit\Framework\TestCase;

/**
 * Test class for Factory
 */
class FactoryTest extends TestCase
{
    public function test()
    {
        $factory = new Factory(function () {
            return new TestClass();
        });

        $closure = $factory->getClosure();
        $this->assertInstanceOf(\Closure::class, $closure);

        $object = $factory->create();
        $this->assertInstanceOf(TestClass::class, $object);

        $firstCall = $factory->getInstance();
        $secondCall = $factory->getInstance();
        $this->assertInstanceOf(TestClass::class, $firstCall);
        $this->assertSame($firstCall, $secondCall);
    }
}
