<?php
namespace Morpher\Ws3Client;

class TokenIncorrectFormat extends AuthenticationError
{
    function __construct(string $message)
    {
        parent::__construct($message);
    }
}
