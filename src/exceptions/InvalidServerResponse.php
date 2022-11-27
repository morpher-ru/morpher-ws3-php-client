<?php
namespace Morpher\Ws3Client;

class InvalidServerResponse extends \Exception
{
    /**
     * @readonly
     */
    public string $response;
    function __construct(string $message="",string $response='')
    {
        parent::__construct($message);
        $this->response=$response;
    }

}