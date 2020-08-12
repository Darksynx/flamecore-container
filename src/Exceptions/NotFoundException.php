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

namespace FlameCore\Container\Exceptions;

use Psr\Container\NotFoundExceptionInterface;

/**
 * This exception is thrown if a container entry is not found.
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class NotFoundException extends ContainerException implements NotFoundExceptionInterface
{
}
