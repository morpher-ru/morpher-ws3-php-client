<?php
namespace Morpher\Ws3Client;

class TokenIncorrectFormat extends InvalidServerResponse
{
    function __construct(string $message, int $code = 0)
    {
        parent::__construct($message, $code);
    }
}