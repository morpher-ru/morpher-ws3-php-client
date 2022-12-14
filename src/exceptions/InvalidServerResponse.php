<?php
namespace Morpher\Ws3Client;

class InvalidServerResponse extends \Exception
{
    public string $response;

    function __construct(string $message, string $response, int $error_code = 0)
    {
        parent::__construct($message, $error_code);
        $this->response = $response;
    }
}