<?php
namespace Morpher\Ws3Client;

use Morpher\Ws3Client\ServiceDenied;


class RequestsDailyLimit extends ServiceDenied
{
    function __construct(string $message = 'Превышен лимит на количество запросов в сутки. Перейдите на следующий тарифный план.',int $code = 0)
    {
        parent::__construct($message,$code);
    }
}
