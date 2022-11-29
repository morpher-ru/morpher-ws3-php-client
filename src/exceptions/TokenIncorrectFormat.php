<?php

namespace Morpher\Ws3Client;

class TokenIncorrectFormat extends \Exception
{
    function __construct(string $message = 'Неверный формат токена.', int $code = 0)
    {
        parent::__construct($message, $code);
    }
}