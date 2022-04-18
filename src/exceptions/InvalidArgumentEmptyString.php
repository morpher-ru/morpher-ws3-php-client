<?php
namespace Morpher\Ws3Client;

class InvalidArgumentEmptyString extends \InvalidArgumentException
{
    function __construct(string $message='Передана пустая строка.',int $code=0)
    {
        parent::__construct($message,$code);
    }
}