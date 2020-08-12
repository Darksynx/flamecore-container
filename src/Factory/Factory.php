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

declare(strict_types=1);

namespace FlameCore\Container\Factory;

/**
 * The Factory class.
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class Factory extends AbstractFactory implements ClosureFactoryInterface
{
    /**
     * @var \Closure
     */
    protected $closure;

    /**
     * Factory constructor.
     *
     * @param \Closure $closure The closure to create the object
     * @param array $arguments The arguments to create the object
     */
    public function __construct(\Closure $closure, array $arguments = [])
    {
        $this->closure = $closure;
        $this->arguments = $arguments;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $arguments = [])
    {
        $factory = $this->closure;

        return $factory($arguments);
    }

    /**
     * {@inheritdoc}
     */
    public function getClosure(): \Closure
    {
        return $this->closure;
    }
}
