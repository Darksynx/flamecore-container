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

use FlameCore\Container\Definition\Definition;
use FlameCore\Container\Exceptions\InvalidDefinitionException;
use PHPUnit\Framework\TestCase;

/**
 * Test class for Definition
 */
class DefinitionTest extends TestCase
{
    public function test()
    {
        $definition = new Definition(TestClass::class);

        $this->assertEquals(TestClass::class, $definition->getType());

        $this->assertTrue($definition->validate(new TestClass()));
        $this->assertFalse($definition->validate(new \stdClass()));
    }

    public function testThrowsExceptionWhenClassNotExists()
    {
        $this->expectException(InvalidDefinitionException::class);
        $this->expectExceptionMessage('non-existing class NonExistingClass as type');

        new Definition('NonExistingClass');
    }
}
