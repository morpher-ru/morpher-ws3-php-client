<?php

namespace Morpher\Ws3Client\Russian;

use Morpher\Ws3Client\InvalidArgumentEmptyString;
use Morpher\Ws3Client\InvalidServerResponse;
use Morpher\Ws3Client\IpBlocked;
use Morpher\Ws3Client\UnknownErrorCode;
use Morpher\Ws3Client\RequestsDailyLimit;
use Morpher\Ws3Client\ServiceDenied;
use Morpher\Ws3Client\TokenIncorrectFormat;
use Morpher\Ws3Client\TokenNotFound;
use Morpher\Ws3Client\TokenRequired;
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
     * @param string $lemma Слово или фраза в именительном падеже
     * @param array $flags Необязательная дополнительная информация для устранения неоднозначностей,
     * массив элементов типа Flags
     * @return DeclensionResult Результат склонения – набор падежных форм
     * @throws InvalidArgumentEmptyString Если $lemma пустая
     * @throws RussianWordsNotFound Не найдено русских слов в параметре $lemma
     * @throws InvalidFlags Недопустимое сочетание флагов (например, мужской + женский)
     * @throws DeclensionNotSupportedUseSpell Если $lemma содержит числительное. Для склонения числительных есть функция Spell().
     * @throws ServiceDenied Если превышен лимит на количество запросов или ваш IP заблокирован
     * @throws TokenNotFound
     * @throws InvalidServerResponse Если сервер вернул неправильный ответ
     * @throws TokenIncorrectFormat
     * @throws TokenRequired
     */
    public function Parse(string $lemma, array $flags = []): DeclensionResult
    {
        if (trim($lemma) == '') throw new InvalidArgumentEmptyString();

        $query = ["s" => $lemma];

        if (!empty($flags))
        {
            $query['flags'] = implode(',',$flags);
        }

        try
        {
            $result_raw = $this->webClient->send("/russian/declension", $query);
        }
        catch (UnknownErrorCode $ex)
        {
            $error_code = $ex->getCode();
            $msg = $ex->getMessage();
            if ($error_code == 5) throw new RussianWordsNotFound($msg);
            if ($error_code == 12) throw new InvalidFlags($msg);
            if ($error_code == 4) throw new DeclensionNotSupportedUseSpell($msg);
            
            throw $ex;
        }

        $result = WebClient::JsonDecode($result_raw);

        $result['И'] = $lemma;

        $declensionResult = new DeclensionResult($result);

        return $declensionResult;
    }

    public function Spell(int $number, string $unit): NumberSpellingResult
    {
        if (empty(trim($unit)))
        {
            throw new \Morpher\Ws3Client\InvalidArgumentEmptyString();
        }

        $queryParam = ["n" => $number,'unit' => $unit];

        try
        {
            $result_raw = $this->webClient->send("/russian/spell",$queryParam);
        }
        catch (\Morpher\Ws3Client\UnknownErrorCode $ex)
        {
            $morpher_code = $ex->getCode();
            $msg = $ex->getMessage();
            if ($morpher_code == 5) throw new RussianWordsNotFound($msg);

            throw $ex;
        }

        $result = WebClient::JsonDecode($result_raw);

        $spellResult = new NumberSpellingResult($result);

        return $spellResult;
    }

    public function SpellOrdinal(int $number, string $unit): NumberSpellingResult
    {
        if (empty(trim($unit)))
        {
            throw new \Morpher\Ws3Client\InvalidArgumentEmptyString();
        }

        $queryParam = ["n" => $number,'unit' => $unit];

        try{

            $result_raw = $this->webClient->send("/russian/spell-ordinal",$queryParam);
        }
        catch (\Morpher\Ws3Client\UnknownErrorCode $ex)
        {
            $morpher_code = $ex->getCode();
            $msg = $ex->getMessage();
            if ($morpher_code == 5) throw new RussianWordsNotFound($msg);

            throw $ex;
        }

        $result = WebClient::JsonDecode($result_raw);

        $spellResult = new NumberSpellingResult($result);

        return $spellResult;
    }


    /**
     * @param $date string|int|DateTimeInterface @date Строка в формате yyyy-MM-dd, int timestamp или DateTimeInterface
     * @throws \Morpher\Ws3Client\InvalidArgumentEmptyString
     * @throws \Morpher\Ws3Client\InvalidServerResponse
     * @throws \InvalidArgumentException
     */
    public function SpellDate($date): DateSpellingResult
    {
        if (is_int($date))
        {
            $date = date('Y-m-d',$date);
        } else 
        if ($date instanceof \DateTimeInterface)
        {
            $date = $date->format('Y-m-d');
        }

        if (!is_string($date))
        {
            throw new \InvalidArgumentException('Неверный тип: нужна строка, int timestamp или DateTimeInterface.');
        }
        if (empty(trim($date)))
        {
            throw new \Morpher\Ws3Client\InvalidArgumentEmptyString();
        }

        $queryParam = ["date" => $date];

        try
        {
            $result_raw = $this->webClient->send("/russian/spell-date",$queryParam);
        }
        catch (\Morpher\Ws3Client\UnknownErrorCode $ex)
        {
            $morpher_code = $ex->getCode();
            $msg = $ex->getMessage();
            if ($morpher_code == 8) throw new IncorrectDateFormat($msg);

            throw $ex;
        }

        $result = WebClient::JsonDecode($result_raw);
        $spellResult = new DateSpellingResult($result);

        return $spellResult;
    }

    public function AdjectiveGenders(string $adj): AdjectiveGenders
    {
        if (trim($adj) == '') throw new \Morpher\Ws3Client\InvalidArgumentEmptyString();
        
        $query = ['s' => $adj];

        try
        {
            $result_raw = $this->webClient->send("/russian/genders",$query);
        }
        catch (\Morpher\Ws3Client\UnknownErrorCode $ex)
        {
            throw $ex;
        }

        $result = WebClient::JsonDecode($result_raw);

        $genders = new AdjectiveGenders($result);
        if ($genders->Feminine == 'ERROR') throw new AdjectiveFormIncorrect();

        return $genders;
    }

    
    public function Adjectivize(string $name): array
    {
        if (trim($name) == '') throw new \Morpher\Ws3Client\InvalidArgumentEmptyString();
        
        $query = "s=".urlencode($name);

        try
        {
            $result_raw = $this->webClient->send("/russian/adjectivize",$query);
        }
        catch (\Morpher\Ws3Client\UnknownErrorCode $ex)
        {
            throw $ex;
        }

        $result = WebClient::JsonDecode($result_raw);

        return $result;
    }

    /**
    * Добавляет знаки ударения и точки над Ё к тексту на русском языке.
    * @param $text string text Текст на русском языке: "На золотом крыльце сидели"
    * @returns string Текст со знаками ударения: "На золото́́м крыльце́ сиде́ли"
    */
    public function AddStressmarks(string $text): string
    {
        if (trim($text) == '') throw new \Morpher\Ws3Client\InvalidArgumentEmptyString();
        
        $headers = $this->webClient->getStandardHeaders();
        $headers['Content-Type'] = 'text/plain; charset=utf-8';
        try
        {
            $result_raw = $this->webClient->send("/russian/addstressmarks",[],'POST',$headers,$text);
        }
        catch (\Morpher\Ws3Client\UnknownErrorCode $ex)
        {
            throw $ex;
        }

        $result = WebClient::JsonDecode($result_raw);

        return $result;
    }
}