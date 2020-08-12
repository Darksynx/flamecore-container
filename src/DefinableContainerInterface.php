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

namespace FlameCore\Container;

use FlameCore\Container\Definition\DefinitionInterface;
use FlameCore\Container\Exceptions\AlreadyInUseException;
use FlameCore\Container\Exceptions\InvalidDefinitionException;
use Psr\Container\ContainerInterface;

/**
 * Describes the interface of a container that exposes methods to define entries.
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
interface DefinableContainerInterface extends ContainerInterface
{
    /**
     * Defines the given entry in the type map.
     *
     * @param string $id The identifier of the entry
     * @param string $type The required type of the value
     *
     * @throws InvalidDefinitionException Non-existing class as type given.
     * @throws AlreadyInUseException The entry to define is already in use.
     */
    public function define($id, $type);

    /**
     * Checks whether the entry with given identifier is defined.
     *
     * @param string $id The identifier of the entry
     *
     * @return bool Returns TRUE if the entry is defined or FALSE if not.
     */
    public function isDefined($id);

    /**
     * Returns the definition of the entry with given identifier.
     *
     * @param string $id The identifier of the entry
     *
     * @return DefinitionInterface|null Returns the definition of the entry if it is defined or NULL if not.
     */
    public function getDefinition($id);
}
