<?php

namespace Morpher\Ws3Client\Russian;

use DateTimeInterface;
use InvalidArgumentException;
use Morpher\Ws3Client\Exceptions\InvalidArgumentEmptyString;
use Morpher\Ws3Client\Exceptions\InvalidServerResponse;
use Morpher\Ws3Client\Exceptions\IpBlocked;
use Morpher\Ws3Client\Exceptions\MorpherError;
use Morpher\Ws3Client\Exceptions\RequestsDailyLimit;
use Morpher\Ws3Client\Exceptions\TokenIncorrectFormat;
use Morpher\Ws3Client\Exceptions\TokenNotFound;
use Morpher\Ws3Client\Exceptions\TokenRequired;
use Morpher\Ws3Client\Russian\Exceptions\AdjectiveFormIncorrect;
use Morpher\Ws3Client\Russian\Exceptions\DeclensionNotSupportedUseSpell;
use Morpher\Ws3Client\Russian\Exceptions\IncorrectDateFormat;
use Morpher\Ws3Client\Russian\Exceptions\InvalidFlags;
use Morpher\Ws3Client\Russian\Exceptions\RussianWordsNotFound;
use Morpher\Ws3Client\WebClient;

class Client
{
	/**
	 * @readonly
	 */
	public UserDict $userDict;
	/**
	 * @readonly
	 */
	private WebClient $webClient;

	public function __construct(WebClient $webClient)
	{
		$this->webClient = $webClient;
		$this->userDict = new UserDict($webClient);
	}

	/**
	 * @throws TokenIncorrectFormat
	 * @throws IpBlocked
	 * @throws TokenNotFound
	 * @throws InvalidServerResponse
	 * @throws RequestsDailyLimit|TokenRequired
	 */
	public function parse(string $lemma, array $flags = []): DeclensionResult
	{
		if (trim($lemma) == '')
		{
			throw new InvalidArgumentEmptyString();
		}

		$query = ['s' => $lemma];

		if (!empty($flags))
		{
			$query['flags'] = implode(',', $flags);
		}

		try
		{
			$result_raw = $this->webClient->send("/russian/declension", $query);
		}
		catch (MorpherError $ex)
		{
			$morpher_code = $ex->getCode();
			$msg = $ex->getMessage();

			if ($morpher_code == 5)
			{
				throw new RussianWordsNotFound($msg);
			}
			if ($morpher_code == 12)
			{
				throw new InvalidFlags($msg);
			}
			if ($morpher_code == 4)
			{
				throw new DeclensionNotSupportedUseSpell($msg);
			}

			throw new InvalidServerResponse("Неизвестный код ошибки");
		}

		$result = WebClient::jsonDecode($result_raw);

		$result['И'] = $lemma;

		return new DeclensionResult($result);
	}

	/**
	 * @throws TokenRequired
	 * @throws RequestsDailyLimit
	 * @throws TokenIncorrectFormat
	 * @throws InvalidServerResponse
	 * @throws TokenNotFound
	 * @throws IpBlocked
	 */
	public function spell(int $number, string $unit): NumberSpellingResult
	{
		if (empty(trim($unit)))
		{
			throw new InvalidArgumentEmptyString();
		}

		$queryParam = ['n' => $number, 'unit' => $unit];

		try
		{
			$result_raw = $this->webClient->send("/russian/spell", $queryParam);
		}
		catch (MorpherError $ex)
		{
			$morpher_code = $ex->getCode();
			$msg = $ex->getMessage();

			if ($morpher_code == 5)
			{
				throw new RussianWordsNotFound($msg);
			}

			throw new InvalidServerResponse("Неизвестный код ошибки");
		}

		$result = WebClient::jsonDecode($result_raw);

		return new NumberSpellingResult($result);
	}

	/**
	 * @throws TokenRequired
	 * @throws RequestsDailyLimit
	 * @throws TokenIncorrectFormat
	 * @throws InvalidServerResponse
	 * @throws TokenNotFound
	 * @throws IpBlocked
	 */
	public function spellOrdinal(int $number, string $unit): NumberSpellingResult
	{
		if (empty(trim($unit)))
		{
			throw new InvalidArgumentEmptyString();
		}

		$queryParam = ['n' => $number, 'unit' => $unit];

		try
		{
			$result_raw = $this->webClient->send("/russian/spell-ordinal", $queryParam);
		}
		catch (MorpherError $ex)
		{
			$morpher_code = $ex->getCode();
			$msg = $ex->getMessage();

			if ($morpher_code == 5)
			{
				throw new RussianWordsNotFound($msg);
			}

			throw new InvalidServerResponse("Неизвестный код ошибки");
		}

		$result = WebClient::jsonDecode($result_raw);

		return new NumberSpellingResult($result);
	}

	//yyyy-MM-dd

	/**
	 * @throws RequestsDailyLimit
	 * @throws TokenRequired
	 * @throws TokenIncorrectFormat
	 * @throws InvalidServerResponse
	 * @throws TokenNotFound
	 * @throws IpBlocked
	 */
	public function spellDate($date): DateSpellingResult  // $date - string, timestamp, DateTimeInterface
	{
		if (is_int($date))
		{
			$date = date('Y-m-d', $date);
		}
		else if ($date instanceof DateTimeInterface)
		{
			$date = $date->format('Y-m-d');
		}

		if (!is_string($date))
		{
			throw new InvalidArgumentException('Неверный тип: нужна строка, int timestamp или DateTimeInterface.');
		}

		if (empty(trim($date)))
		{
			throw new InvalidArgumentEmptyString();
		}

		$queryParam = ['date' => $date];

		try
		{
			$result_raw = $this->webClient->send("/russian/spell-date", $queryParam);
		}
		catch (MorpherError $ex)
		{
			$morpher_code = $ex->getCode();
			$msg = $ex->getMessage();

			if ($morpher_code == 8)
			{
				throw new IncorrectDateFormat($msg);
			}

			throw new InvalidServerResponse("Неизвестный код ошибки");
		}

		$result = WebClient::jsonDecode($result_raw);

		return new DateSpellingResult($result);
	}

	/**
	 * @throws RequestsDailyLimit
	 * @throws TokenRequired
	 * @throws TokenIncorrectFormat
	 * @throws InvalidServerResponse
	 * @throws TokenNotFound
	 * @throws IpBlocked
	 */
	public function adjectiveGenders(string $adj): AdjectiveGenders
	{
		if (trim($adj) == '')
		{
			throw new InvalidArgumentEmptyString();
		}

		$query = ['s' => $adj];

		try
		{
			$result_raw = $this->webClient->send("/russian/genders", $query);
		}
		catch (MorpherError $ex)
		{
			throw new InvalidServerResponse("Неизвестный код ошибки");
		}

		$result = WebClient::jsonDecode($result_raw);

		$genders = new AdjectiveGenders($result);
		if ($genders->feminine == 'ERROR')
		{
			throw new AdjectiveFormIncorrect();
		}

		return $genders;
	}

	/**
	 * @throws TokenRequired
	 * @throws RequestsDailyLimit
	 * @throws InvalidServerResponse
	 * @throws TokenIncorrectFormat
	 * @throws TokenNotFound
	 * @throws IpBlocked
	 */
	public function adjectivize(string $name): array
	{
		if (trim($name) == '')
		{
			throw new InvalidArgumentEmptyString();
		}

		$query = 's=' . urlencode($name);

		try
		{
			$result_raw = $this->webClient->send("/russian/adjectivize", $query);
		}
		catch (MorpherError $ex)
		{
			throw new InvalidServerResponse("Неизвестный код ошибки");
		}

		return WebClient::jsonDecode($result_raw);
	}

	/**
	 * @throws RequestsDailyLimit
	 * @throws TokenRequired
	 * @throws InvalidServerResponse
	 * @throws TokenIncorrectFormat
	 * @throws TokenNotFound
	 * @throws IpBlocked
	 */
	public function addStressMarks(string $text): string
	{
		if (trim($text) == '')
		{
			throw new InvalidArgumentEmptyString();
		}

		$headers = $this->webClient->getStandartHeaders();
		$headers['Content-Type'] = 'text/plain; charset=utf-8';

		try
		{
			$result_raw = $this->webClient->send("/russian/addstressmarks", [], 'POST', $headers, $text);
		}
		catch (MorpherError $ex)
		{
			throw new InvalidServerResponse("Неизвестный код ошибки");
		}

		return WebClient::jsonDecode($result_raw);
	}
}
