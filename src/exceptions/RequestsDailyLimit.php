<?php

namespace Morpher\Ws3Client;

class RequestsDailyLimit extends ServiceDenied
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
