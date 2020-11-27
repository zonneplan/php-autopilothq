<?php

namespace Autopilot\Exceptions;

use Exception;

class ExceededContactUploadLimitException extends Exception
{
    public static function create(int $itemsFound): self
    {
        return new static(sprintf(
            'Maximum contact upload is 100, found %d items',
            $itemsFound
        ));
    }
}
