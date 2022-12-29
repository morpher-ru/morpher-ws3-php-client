<?php
namespace Morpher\Ws3Client;

/**
 * Для выполнения данной операции нужно указать в запросе токен.
 */
class TokenRequired extends \Exception
{
    function __construct(string $message)
    {
        parent::__construct($message);
    }
}