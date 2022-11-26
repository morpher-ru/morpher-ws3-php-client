<?php

namespace Morpher\Ws3Client\Ukrainian;

use Morpher\Ws3Client\Exceptions\InvalidArgumentEmptyString;
use Morpher\Ws3Client\Exceptions\InvalidServerResponse;
use Morpher\Ws3Client\Exceptions\IpBlocked;
use Morpher\Ws3Client\Exceptions\MorpherError;
use Morpher\Ws3Client\Exceptions\RequestsDailyLimit;
use Morpher\Ws3Client\Exceptions\TokenIncorrectFormat;
use Morpher\Ws3Client\Exceptions\TokenNotFound;
use Morpher\Ws3Client\Exceptions\TokenRequired;
use Morpher\Ws3Client\WebClient;
use Morpher\Ws3Client\Ukrainian\Exceptions\InvalidFlags;
use Morpher\Ws3Client\Ukrainian\Exceptions\UkrainianWordsNotFound;

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
	 * @throws TokenRequired
	 * @throws RequestsDailyLimit
	 * @throws InvalidServerResponse
	 * @throws TokenIncorrectFormat
	 * @throws TokenNotFound
	 * @throws IpBlocked
	 */
	public function parse(string $lemma, array $flags = []): DeclensionResult
	{
		if (trim($lemma) == '')
		{
			throw new InvalidArgumentEmptyString();
		}

		$query = ["s" => $lemma];
		if (!empty($flags))
		{
			$query['flags'] = implode(',', $flags);
		}

		try
		{
			$result_raw = $this->webClient->send("/ukrainian/declension", $query);
		}
		catch (MorpherError $ex)
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

			throw new InvalidServerResponse("Неизвестный код ошибки");
		}

		$result = WebClient::jsonDecode($result_raw);

		$result['Н'] = $lemma;

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
			$result_raw = $this->webClient->send("/ukrainian/spell", $queryParam);
		}
		catch (MorpherError $ex)
		{
			//$morpher_code = $ex->getCode();
			//$msg = $ex->getMessage();

			throw new InvalidServerResponse("Неизвестный код ошибки");
		}

		$result = WebClient::jsonDecode($result_raw);

		return new NumberSpellingResult($result);
	}
}
