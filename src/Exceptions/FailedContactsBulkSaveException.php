<?php

namespace Autopilot\Exceptions;

use Exception;

class FailedContactsBulkSaveException extends Exception
{
    public static function create(?string $email = null): self
    {
        return new static(sprintf(
            'Failed to upload contacts in bulk. Failed contact: "%s"',
            $email
        ));
    }
}
