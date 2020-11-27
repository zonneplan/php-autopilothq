<?php

namespace Autopilot\Exceptions;

use Exception;

class CannotOverwriteContactIdException extends Exception
{
    public static function create($originalContactId, $newContactId): self
    {
        return new static(sprintf(
            'Original contact id (%s) is different from contact id (%s) to save',
            $originalContactId,
            $newContactId
        ));
    }
}
