<?php

namespace Morpher\Ws3Client;

class ServiceDenied extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}