<?php

namespace Morpher\Ws3Client;

class ServerError extends \Exception
{
    public function __construct(\Throwable $ex)
    {
        parent::__construct("Ошибка сервера", 0, $ex);
    }
}