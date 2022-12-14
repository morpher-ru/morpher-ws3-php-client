<?php
namespace Morpher\Ws3Client;

class TokenNotFound extends \Exception
{
    function __construct(string $message)
    {
        parent::__construct($message);
    }
}