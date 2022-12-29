<?php

namespace Morpher\Ws3Client\Russian;

use DateTimeInterface;
use InvalidArgumentException;
use Morpher\Ws3Client\InvalidArgumentEmptyString;
use Morpher\Ws3Client\InvalidServerResponse;
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
     * Склоняет по падежам и числам на русском языке.
     * @param string $lemma Слово или фраза в именительном падеже
     * @param array $flags Необязательная дополнительная информация для устранения неоднозначностей,
     * массив элементов типа Flags
     * @return DeclensionResult Результат склонения – набор падежных форм
     * @throws InvalidArgumentEmptyString Если $lemma пустая
     * @throws RussianWordsNotFound Не найдено русских слов в параметре $lemma
     * @throws InvalidFlags Недопустимое сочетание флагов (например, мужской + женский)
     * @throws DeclensionNotSupportedUseSpell Если $lemma содержит числительное. Для склонения числительных есть функция Spell().
     * @throws SystemError
     */
    public function Parse(string $lemma, array $flags = []): DeclensionResult
    {
        $query = ["s" => $lemma];

        if (!empty($flags))
        {
            $query['flags'] = implode(',', $flags);
        }

        try
        {
            $result_raw = $this->webClient->send("/russian/declension", $query);

            $result = WebClient::jsonDecode($result_raw);

            $result['И'] = $lemma;

            $declensionResult = new DeclensionResult($result);

            return $declensionResult;
        }
        catch (UnknownErrorCode $ex)
        {
            $error_code = $ex->getCode();
            $msg = $ex->getMessage();
            if ($error_code == 5) throw new RussianWordsNotFound($msg);
            if ($error_code == 6) throw new InvalidArgumentEmptyString($msg);
            if ($error_code == 12) throw new InvalidFlags($msg);
            if ($error_code == 4) throw new DeclensionNotSupportedUseSpell($msg);

            throw $ex;
        }
    }

    /**
     * Строит пропись числа и единицы измерения во всех падежах (сто рублей, ста рублям...)
     * @param int $number Число
     * @param string $unit Единица измерения (метр, календарный день, доллар США...)
     * @return NumberSpellingResult Набор падежных форм для $number и $unit
     * @throws InvalidArgumentEmptyString Если параметр $unit пустой
     * @throws SystemError
     */
    public function Spell(int $number, string $unit): NumberSpellingResult
    {
        return $this->spellNumber($number, $unit, "/russian/spell");
    }

    /**
     * Помогает строить фразы типа «пятый этаж», «сто первый километр»
     * из числа и единицы измерения во всех падежах (пятого этажа, пятом этаже).
     * @param int $number Число
     * @param string $unit Единица измерения (этаж, километр...)
     * @return NumberSpellingResult Набор падежных форм для $number и $unit
     * @throws InvalidArgumentEmptyString Если параметр $unit пустой
     * @throws SystemError
     */
    public function SpellOrdinal(int $number, string $unit): NumberSpellingResult
    {
        return $this->spellNumber($number, $unit, "/russian/spell-ordinal");
    }

    /**
     * @param int $number
     * @param string $unit
     * @param string $urlSlug
     * @return NumberSpellingResult
     * @throws InvalidServerResponse
     * @throws SystemError
     */
    function spellNumber(int $number, string $unit, string $urlSlug): NumberSpellingResult
    {
        $queryParam = ["n" => $number, 'unit' => $unit];

        try
        {
            $result_raw = $this->webClient->send($urlSlug, $queryParam);

            $result = WebClient::jsonDecode($result_raw);

            $spellResult = new NumberSpellingResult($result);

            return $spellResult;
        }
        catch (UnknownErrorCode $ex)
        {
            $error_code = $ex->getCode();
            $msg = $ex->getMessage();

            if ($error_code == 5) throw new RussianWordsNotFound($msg);
            if ($error_code == 6) throw new InvalidArgumentEmptyString($msg);

            throw $ex;
        }
    }

    /**
     * @param $date string|int|DateTimeInterface @date Строка в формате yyyy-MM-dd, int timestamp или DateTimeInterface
     * @throws InvalidArgumentEmptyString
     * @throws SystemError
     * @throws InvalidArgumentException
     */
    public function SpellDate($date): DateSpellingResult
    {
        if (is_int($date))
        {
            $date = date('Y-m-d',$date);
        }
        else if ($date instanceof DateTimeInterface)
        {
            $date = $date->format('Y-m-d');
        }

        if (!is_string($date))
        {
            throw new InvalidArgumentException('Неверный тип: нужна строка, int timestamp или DateTimeInterface.');
        }

        $queryParam = ["date" => $date];

        try
        {
            $result_raw = $this->webClient->send("/russian/spell-date", $queryParam);

            $result = WebClient::jsonDecode($result_raw);
            $spellResult = new DateSpellingResult($result);

            return $spellResult;
        }
        catch (UnknownErrorCode $ex)
        {
            $error_code = $ex->getCode();
            $msg = $ex->getMessage();
            if ($error_code == 8) throw new IncorrectDateFormat($msg);
            if ($error_code == 6) throw new InvalidArgumentEmptyString($msg);

            throw $ex;
        }
    }

    /**
     * Склоняет прилагательные по родам (красивый -> красивая, красивое, красивые).
     * @param string $adjective
     * @return AdjectiveGenders
     * @throws SystemError
     * @throws InvalidArgumentEmptyString
     */
    public function AdjectiveGenders(string $adjective): AdjectiveGenders
    {
        $query = ['s' => $adjective];

        try {
            $result_raw = $this->webClient->send("/russian/genders", $query);

            $result = WebClient::jsonDecode($result_raw);

            $genders = new AdjectiveGenders($result);

            $f = $genders->Feminine;
            $n = $genders->Neuter;
            $p = $genders->Plural;

            if ($f == 'ERROR' && $n == 'ERROR' && $p == 'ERROR') {
                throw new AdjectiveFormIncorrect();
            }

            return $genders;
        }
        catch (UnknownErrorCode $ex)
        {
            if ($ex->getCode() == 6) throw new InvalidArgumentEmptyString($ex->getMessage());

            throw $ex;
        }
    }

    /**
     * Образует прилагательные от названий городов и стран (Москва -> московский).
     * @param string $name Название города или страны
     * @return array
     * @throws SystemError
     */
    public function Adjectivize(string $name): array
    {
        $query = ['s' => $name];

        try
        {
            $result_raw = $this->webClient->send("/russian/adjectivize", $query);

            $result = WebClient::jsonDecode($result_raw);

            return $result;
        }
        catch (UnknownErrorCode $ex)
        {
            if ($ex->getCode() == 6) throw new InvalidArgumentEmptyString($ex->getMessage());
            throw $ex;
        }
    }

    /**
     * Добавляет знаки ударения и точки над Ё к тексту на русском языке.
     * @param $text string Текст на русском языке: "На золотом крыльце сидели"
     * @return string
     * @throws SystemError
     */
    public function AddStressmarks(string $text): string
    {
        $headers = $this->webClient->getStandardHeaders();
        $headers['Content-Type'] = 'text/plain; charset=utf-8';
        $result_raw = $this->webClient->send("/russian/addstressmarks",[],'POST',$headers,$text);
        $result = WebClient::jsonDecode($result_raw);
        return $result;
    }
}