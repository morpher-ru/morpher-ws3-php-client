<?php
namespace Morpher\Ws3Client\Ukrainian;


use Morpher\Ws3Client\InvalidArgumentEmptyString;
use Morpher\Ws3Client\SystemError;
use Morpher\Ws3Client\UnknownErrorCode;
use Morpher\Ws3Client\WebClient;


class Client
{
    private WebClient $webClient;
    public UserDict $userDict;
    
    public function __construct(WebClient $webClient)
    {
        $this->webClient = $webClient;
        $this->userDict = new UserDict($webClient);
    }

    /**
     * Склоняет по падежам ФИО на украинском языке.
     * @param string $lemma ФИО
     * @param array $flags
     * @return DeclensionResult
     * @throws InvalidArgumentEmptyString
     * @throws UkrainianWordsNotFound
     * @throws InvalidFlags
     * @throws SystemError
     */
    public function Parse(string $lemma,array $flags = []): DeclensionResult
    {
        $query = ["s" => $lemma];

        if (!empty($flags))
        {
            $query['flags'] = implode(',', $flags);
        }

        try
        {
            $result_raw = $this->webClient->send("/ukrainian/declension", $query, 'GET');

            $result = WebClient::JsonDecode($result_raw);

            $result['Н'] = $lemma;

            $declensionResult = new DeclensionResult($result);

            return $declensionResult;
        }
        catch (UnknownErrorCode $ex)
        {
            $error_code = $ex->getCode();
            $msg = $ex->getMessage();

            if ($error_code == 6) throw new InvalidArgumentEmptyString($msg);
            if ($error_code == 5) throw new UkrainianWordsNotFound($msg);
            if ($error_code == 12) throw new InvalidFlags($msg);
            
            throw $ex;
        }
    }

    /**
     * Помогает строить фразы типа «двісті метрів», «три учасники»
     * из числа и единицы измерения
     * @param int $number Число
     * @param string $unit Единица измерения
     * @return NumberSpellingResult
     * @throws SystemError
     * @throws InvalidArgumentEmptyString
     */
    public function Spell(int $number, string $unit): NumberSpellingResult
    {
        $queryParam = ["n" => $number, 'unit' => $unit];

        try
        {
            $result_raw = $this->webClient->send("/ukrainian/spell", $queryParam);

            $result = WebClient::JsonDecode($result_raw);
            $spellResult = new NumberSpellingResult($result);

            return $spellResult;
        }
        catch (UnknownErrorCode $ex)
        {
            $error_code = $ex->getCode();
            $msg = $ex->getMessage();

            if ($error_code == 6) throw new InvalidArgumentEmptyString($msg);

            throw $ex;
        }
    }
}
