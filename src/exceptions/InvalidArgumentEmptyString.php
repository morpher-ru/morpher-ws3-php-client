<?php
namespace Morpher\Ws3Client;

class InvalidArgumentEmptyString extends \InvalidArgumentException
{
    function __construct(string $message = 'Передана пустая строка.')
    {
        parent::__construct($message);
    }
}