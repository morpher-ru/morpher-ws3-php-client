<?php
namespace Morpher\Ws3Client;


class ServiceDenied extends \Exception
{
    function __construct(string $message, int $code)
    {
        parent::__construct($message, $code);
    }
}