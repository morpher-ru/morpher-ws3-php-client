<?php
namespace Morpher\Ws3Client;

class InvalidServerResponse extends \Exception
{
    public readonly string $response;
    function __construct(string $message="",string $response='')
    {
        parent::__construct($message);
        $this->response=$response;
    }
}