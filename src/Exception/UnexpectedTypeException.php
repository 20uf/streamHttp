<?php

/*
 * This file is part of the StreamHttp project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace StreamHttp\Exception;

use InvalidArgumentException;

/**
 * Exception thrown if an argument is not of the expected type.
 *
 * @author Michael COULLERET <michael@coulleret.pro>
 */
class UnexpectedTypeException extends InvalidArgumentException
{
    /**
     * constructor UnexpectedTypeException
     *
     * @param string $value
     * @param string $expectedType
     */
    public function __construct($value, $expectedType)
    {
        parent::__construct(sprintf('Expected argument of type "%s", "%s" given', $expectedType, is_object($value) ? get_class($value) : gettype($value)));
    }
}
