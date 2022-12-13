<?php
namespace Morpher\Ws3Client;

class IpBlocked extends ServiceDenied
{
    function __construct(string $message)
    {
        parent::__construct($message, 3);
    }
}