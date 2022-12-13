<?php
namespace Morpher\Ws3Client;

class InvalidServerResponse extends \Exception
{
    function __construct(string $message)
    {
        parent::__construct($message);
    }
}