<?php

namespace Autopilot\Exceptions;

use Exception;

class ExceededContactUploadLimitException extends Exception
{
    public static function create(): self
    {
        return new static('Maximum contact upload is 100');
    }
}
