<?php

namespace Morpher\Ws3Client;

class ConnectionError extends \Exception
{
    public function __construct(\Throwable $ex)
    {
        parent::__construct("Ошибка связи", 0, $ex);
    }
}