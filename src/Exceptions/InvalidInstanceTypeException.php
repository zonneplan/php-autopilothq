<?php

namespace Autopilot\Exceptions;

use Exception;

class InvalidInstanceTypeException extends Exception
{
    public static function create(string $instance): self
    {
        return new static(sprintf(
            'Instance must be of type "%s"',
            $instance
        ));
    }
}
