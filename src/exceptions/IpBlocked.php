<?php

namespace Morpher\Ws3Client;

class IpBlocked extends \Exception
{
    function __construct(string $message = 'IP заблокирован.', int $code = 0)
    {
        parent::__construct($message, $code);
    }
}