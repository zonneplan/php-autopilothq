<?php

namespace Autopilot\Exceptions;

use Exception;

class MethodNotImplementedException extends Exception
{
    public static function create(string $method): self
    {
        return new static(sprintf(
            'The %s method is not yet implemented',
            $method
        ));
    }
}
