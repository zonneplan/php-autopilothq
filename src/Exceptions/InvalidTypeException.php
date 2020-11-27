<?php

namespace Autopilot\Exceptions;

use Exception;

class InvalidTypeException extends Exception
{
    public static function create(?string $type = null): self
    {
        return new static((is_null($type) ? 'Invalid data type.' : '"' . $type . '" is not a valid Autopilot data type'));
    }
}
