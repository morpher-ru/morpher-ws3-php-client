<?php

namespace Morpher\Ws3Client;

use Morpher\Ws3Client\ServiceDenied;

class RequestsDailyLimit extends ServiceDenied
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
