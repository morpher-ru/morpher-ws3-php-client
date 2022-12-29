<?php

namespace Morpher\Ws3Client;

class ServerError extends SystemError
{
    public function __construct(\Throwable $ex)
    {
        parent::__construct("Ошибка сервера", 0, $ex);
    }
}