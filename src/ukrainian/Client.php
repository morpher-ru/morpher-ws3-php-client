<?php

namespace Morpher\Ws3Client\Ukrainian;

use Morpher\Ws3Client\WebClient;

//use Morpher\Ws3Client\Ukrainian\DeclensionResult;

class Client
{
    private WebClient $webClient;

    public UserDict $userDict;

    public function __construct(WebClient $webClient)
    {
        $this->webClient = $webClient;
        $this->userDict = new UserDict($webClient);
    }

    public function Parse(string $lemma, array $flags = []): DeclensionResult
    {
        if (trim($lemma) == '')
        {
            throw new \Morpher\Ws3Client\InvalidArgumentEmptyString();
        }

        $query = ["s" => $lemma];
        if (!empty($flags))
        {
            $query['flags'] = implode(',', $flags);
        }

        $result_raw = "";
        try
        {

            $result_raw = $this->webClient->send("/ukrainian/declension", $query, 'GET');
        }
        catch (\Morpher\Ws3Client\MorpherError $ex)
        {
            $morpher_code = $ex->getCode();
            $msg = $ex->getMessage();
            if ($morpher_code == 5)
            {
                throw new UkrainianWordsNotFound($msg);
            }
            if ($morpher_code == 12)
            {
                throw new InvalidFlags($msg);
            }

            throw new \Morpher\Ws3Client\InvalidServerResponse("Неизвестный код ошибки");
        }

        $result = WebClient::JsonDecode($result_raw);
        //
        //parse result

        $result['Н'] = $lemma;
        $declensionResult = new DeclensionResult($result);

        return $declensionResult;
    }

    public function Spell(int $number, string $unit): NumberSpellingResult
    {
        if (empty(trim($unit)))
        {
            throw new \Morpher\Ws3Client\InvalidArgumentEmptyString();
        }

        $queryParam = ["n" => $number, 'unit' => $unit];

        $result_raw = "";
        try
        {

            $result_raw = $this->webClient->send("/ukrainian/spell", $queryParam, 'GET');
        }
        catch (\Morpher\Ws3Client\MorpherError $ex)
        {
            //$morpher_code=$ex->getCode();
            //$msg=$ex->getMessage();

            throw new \Morpher\Ws3Client\InvalidServerResponse("Неизвестный код ошибки");
        }

        $result = WebClient::JsonDecode($result_raw);
        $spellResult = new NumberSpellingResult($result);

        return $spellResult;

    }

}