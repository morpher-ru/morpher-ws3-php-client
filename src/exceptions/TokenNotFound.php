<?php

namespace Morpher\Ws3Client;


class TokenNotFound extends AuthenticationError
{
    function __construct(string $message)
    {
        parent::__construct($message);
    }
}