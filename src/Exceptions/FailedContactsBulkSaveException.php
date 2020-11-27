<?php

namespace Autopilot\Exceptions;

use Exception;

class FailedContactsBulkSaveException extends Exception
{
    public static function create(?string $message = null): self
    {
        return new static('contacts bulk upload failed' . (is_null($message) ? '' : ': ' . $message));
    }
}
