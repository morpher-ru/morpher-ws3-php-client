<?php
namespace Morpher\Ws3Client;

abstract class UserDictBase
{
    protected WebClient $webClient;

    protected string $endpoint;

    protected string $CorrectionEntryClassName;
    
    function __construct(WebClient $webClient, string $endpoint, string $CorrectionEntryClassName)
    {
        $this->webClient = $webClient;
        $this->endpoint = $endpoint;
        $this->CorrectionEntryClassName = $CorrectionEntryClassName;
    }

    /**
     * @throws TokenRequired
     * @throws SystemError
     * @throws \InvalidArgumentException
     */
    protected function addOrUpdateBase(CorrectionEntryInterface $entry): void
    {
        if (!($entry instanceof $this->CorrectionEntryClassName))
        {
            throw new \InvalidArgumentException("\$entry не является экземпляром подходящего класса.");
        }

        if (!$entry->SingularNominativeExists())
        {
            throw new \InvalidArgumentException("Обязательно должен быть указан именительный падеж единственного числа.");
        }

        $formParam = $entry->getArrayForRequest();

        if (count($formParam) < 2)
        {
            throw new \InvalidArgumentException("Нужно указать хотя бы одну косвенную форму.");
        }

        try
        {
            $responseBody = $this->webClient->send($this->endpoint, [], 'POST', null, null, $formParam);
            if (!empty($responseBody))
            {
                throw new InvalidServerResponse('Ожидался пустой ответ.', $responseBody);
            }
        }
        catch (UnknownErrorCode $ex)
        {
            // todo: проверить ошибку 6
            if ($ex->getCode() == 25) throw new TokenRequired($ex->getMessage());

            throw $ex;
        }
    }

    /**
     * @throws SystemError
     * @throws TokenRequired
     * @throws InvalidArgumentEmptyString
     */
    public function remove(string $NominativeForm): bool
    {
        $queryParam = ["s" => $NominativeForm];

        try
        {
            $responseBody = $this->webClient->send($this->endpoint, $queryParam, 'DELETE');

            $result = WebClient::jsonDecode($responseBody);

            if (!is_bool($result))
            {
                throw new InvalidServerResponse('Ожидался bool.', $responseBody);
            }

            return $result;
        }
        catch (UnknownErrorCode $ex)
        {
            $error_code = $ex->getCode();
            $msg = $ex->getMessage();

            if ($error_code == 25) throw new TokenRequired($msg);
            if ($error_code == 6) throw new InvalidArgumentEmptyString($msg);

            throw $ex;
        }
    }

    /**
     * @throws SystemError
     * @throws TokenRequired
     */
    public function getAll(): array
    {
        try
        {
            $result_raw = $this->webClient->send($this->endpoint);

            $result = WebClient::jsonDecode($result_raw);

            $array = array_map(function (array $item) { return new $this->CorrectionEntryClassName($item);}, $result );

            return $array;
        }
        catch (UnknownErrorCode $ex)
        {
            if ($ex->getCode() == 25) throw new TokenRequired($ex->getMessage());

            throw $ex;
        }
    }
}