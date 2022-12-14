<?php
namespace Morpher\Ws3Client;

class TokenIncorrectFormat extends TokenNotFound
{
    function __construct(string $message, int $code = 0)
    {
        parent::__construct($message, $code);
    }
}