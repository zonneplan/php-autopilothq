<?php

namespace Autopilot\Exceptions;

use Exception;

class CannotOverwriteContactIdException extends Exception
{
    public static function create(): self
    {
        return new static('Saved contact id is different from current contact id');
    }
}
