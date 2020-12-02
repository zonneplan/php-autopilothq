<?php

namespace Autopilot\Exceptions;

use Exception;

class TypeMisMatchException extends Exception
{
    public static function create(string $field, string $expected, ?string $type = null, ?string $value = null): self
    {
        return new static(sprintf(
            'Type value mismatch! Expected: %s%s on field %s with value %s',
            $expected,
            (is_null($type) ? '' : ', got: ' . $type),
            $field,
            $value
        ));
    }
}
