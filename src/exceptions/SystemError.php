<?php

namespace Morpher\Ws3Client;

class SystemError extends \Exception
{
    function __construct(string $message, int $code = 0, \Throwable $ex = null)
    {
        parent::__construct($message, $code, $ex);
    }
}