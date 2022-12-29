<?php
namespace Morpher\Ws3Client;

/**
 * Выбрасывается, если сервер вернул неожиданный ответ.
 * На практике скорее всего означает, что у вас устаревшая версия клиента.
 */
class InvalidServerResponse extends SystemError
{
    public string $response;

    function __construct(string $message, string $response, int $error_code = 0)
    {
        parent::__construct($message, $error_code);
        $this->response = $response;
    }
}