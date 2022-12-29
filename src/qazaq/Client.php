<?php
namespace Morpher\Ws3Client\Qazaq;


use Morpher\Ws3Client\InvalidArgumentEmptyString;
use Morpher\Ws3Client\SystemError;
use Morpher\Ws3Client\UnknownErrorCode;
use Morpher\Ws3Client\WebClient;

class Client
{
    private WebClient $webClient;
    
    public function __construct(WebClient $webClient)
    {
        $this->webClient = $webClient;
    }

    /**
     * Склоняет по падежам, лицам и числам на казахском языке.
     * @param string $lemma Слово или фраза для склонения
     * @return DeclensionResult Результат склонения
     * @throws SystemError
     * @throws QazaqWordsNotFound
     * @throws InvalidArgumentEmptyString
     */
    public function Parse(string $lemma): DeclensionResult
    {
        $query = ["s" => $lemma];

        try
        {
            $result_raw = $this->webClient->send("/qazaq/declension", $query);
        }
        catch (UnknownErrorCode $ex)
        {
            $error_code = $ex->getCode();
            $msg = $ex->getMessage();

            if ($error_code == 5) throw new QazaqWordsNotFound($msg);
            if ($error_code == 6) throw new InvalidArgumentEmptyString($msg);

            throw $ex;
        }

        $result = WebClient::JsonDecode($result_raw);

        $result['A'] = $lemma;

        $declensionResult = new DeclensionResult($result);

        return $declensionResult;
    }
}