<?php

namespace Morpher\Ws3Client;

class ServiceDenied extends SystemError
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}