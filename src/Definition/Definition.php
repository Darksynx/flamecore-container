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

declare(strict_types=1);

namespace FlameCore\Container\Definition;

use FlameCore\Container\Factory\Factory;
use FlameCore\Container\Exceptions\InvalidDefinitionException;
use ReflectionFunction;

/**
 * The Definition class.
 *
 * @author   Christian Neff <christian.neff@gmail.com>
 */
class Definition implements DefinitionInterface
{
    /**
     * The required type
     *
     * @var string
     */
    protected $type;

    /**
     * Definition constructor.
     *
     * @param string $type The required type
     *
     * @throws InvalidDefinitionException Non-existing class as type given.
     */
    public function __construct(string $type)
    {
        if (!class_exists($type)) {
            throw new InvalidDefinitionException(sprintf('Cannot use non-existing class %s as type', $type));
        }

        $this->type = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value): bool
    {
        if ($value instanceof Factory) {
            $closure = $value->getClosure();
            $reflection = new ReflectionFunction($closure);

            if ($returnType = $reflection->getReturnType()) {
                return $returnType->getName() === $this->type;
            }
        }

        if (!is_object($value) || !is_a($value, $this->type)) {
            return false;
        }

        return true;
    }
}
