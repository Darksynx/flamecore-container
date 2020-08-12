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

namespace FlameCore\Container;

use FlameCore\Container\Exceptions\NotFoundException;
use FlameCore\Container\Exceptions\WrongTypeException;

/**
 * Describes the interface of a container that exposes a method to create new object instances from a factory entry.
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
interface FactoryContainerInterface
{
    /**
     * Create a new object instance from a factory entry.
     *
     * @param string $id The identifier of the entry
     * @param array $arguments The arguments to create the object
     *
     * @return object
     *
     * @throws WrongTypeException The entry is not a factory.
     * @throws NotFoundException No entry was found for this identifier.
     */
    public function make($id, array $arguments = []);
}