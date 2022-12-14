<?php
namespace Morpher\Ws3Client;

class UnknownErrorCode extends InvalidServerResponse
{
    function __construct(int $error_code, string $message, string $response)
    {
        parent::__construct($message, $response, $error_code);
    }
}