<?php

namespace Autopilot\Exceptions;

use Exception;

class AutopilotException extends Exception
{
    /**
     * GET, POST, DELETE
     *
     * @var string
     */
    protected $action;

    /**
     * @var string
     */
    protected $reason;

    /**
     * @var string
     */
    protected $resource;

    /**
     * @var string
     */
    protected $response;

    /**
     * client vs server
     *
     * @var string
     */
    protected $type;

    /**
     * Constructor
     *
     * @param null $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($message = null, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, (int)$code, $previous);

        $this->parseResponseMessage($message);
    }

    /**
     * Update message
     *
     * NOTE: not sure if this is a good idea though
     *
     * @param $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * Get HTTP action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Get reason (if exists)
     *
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Update reason
     *
     * @param $reason
     *
     * @return mixed
     */
    public function setReason($reason)
    {
        return $this->reason = $reason;
    }

    /**
     * Get resource
     *
     * @return string
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Get response from Autopilot
     *
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Get error type (server vs client)
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Extract and parse the JSON message in the Autopilot error response message
     *
     * @param $message
     */
    protected function parseJsonError($message)
    {
        $matches = [];
        preg_match('/{"(\s|\w|\n|:|\.|\,|")+"}/', $message, $matches);
        if (sizeof($matches) > 0) {
            $errors = json_decode($matches[0]);
            $this->message = $errors->message;
            if (isset($errors->error)) {
                $this->reason = $errors->error;
            } elseif (isset($errors->code)) {
                $this->reason = $errors->code;
            }
        } else {
            // if empty json, set message field to empty as well
            $this->message = '';
        }
    }

    /**
     * Extract API information from Autopilot error response message
     *
     * @param $message
     */
    protected function parseResponseMessage($message)
    {
        $path = explode('`', $message);
        if (sizeof($path) === 5) {
            if (strpos($path[0], 'Client') !== false) {
                $this->type = 'CLIENT';
            } elseif (strpos($path[0], 'Server') !== false) {
                $this->type = 'SERVER';
            }
            $resource = explode(' ', $path[1]);
            $this->action = $resource[0];
            $this->resource = $resource[1];
            $this->response = $path[3];

            $this->parseJsonError($path[4]);
        }
    }

    /**
     * Import exception into Autopilot Exception
     *
     * @param Exception $e
     *
     * @return static
     */
    public static function fromExisting(Exception $e)
    {
        return new static($e->getMessage(), $e->getCode(), $e->getPrevious());
    }
}
