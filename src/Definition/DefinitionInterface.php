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
 * @link     https://www.flamecroe.org
 * @license  https://opensource.org/licenses/MIT MIT License
 */

namespace FlameCore\Container\Definition;

/**
 * Interface DefinitionInterface
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
interface DefinitionInterface
{
    /**
     * Returns the required type.
     *
     * @return string
     */
    public function getType(): string;

    /**
     * Validates the given value according to the definition.
     *
     * @param mixed $value The value to validate
     *
     * @return bool
     */
    public function validate($value): bool;
}
