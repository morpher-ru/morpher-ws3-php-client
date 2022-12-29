<?php

namespace Morpher\Ws3Client;

class AuthenticationError extends SystemError
{
    function __construct(string $message)
    {
        parent::__construct($message);
    }
}