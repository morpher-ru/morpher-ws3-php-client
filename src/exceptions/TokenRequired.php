<?php
namespace Morpher\Ws3Client;

class TokenRequired extends \Exception
{
    function __construct(string $message='Требуется указать токен.',int $code=0)
    {
        parent::__construct($message,$code);
    }
}