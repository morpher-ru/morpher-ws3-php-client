<?php

namespace Morpher\Ws3Client\Qazaq;

use Morpher\Ws3Client\Exceptions\InvalidArgumentEmptyString;
use Morpher\Ws3Client\Exceptions\InvalidServerResponse;
use Morpher\Ws3Client\Exceptions\IpBlocked;
use Morpher\Ws3Client\Exceptions\MorpherError;
use Morpher\Ws3Client\Exceptions\RequestsDailyLimit;
use Morpher\Ws3Client\Exceptions\TokenIncorrectFormat;
use Morpher\Ws3Client\Exceptions\TokenNotFound;
use Morpher\Ws3Client\Exceptions\TokenRequired;
use Morpher\Ws3Client\Qazaq\Exceptions\QazaqWordsNotFound;
use Morpher\Ws3Client\WebClient;

class Client
{
	/**
	 * @readonly
	 */
	private WebClient $webClient;

	public function __construct(WebClient $webClient)
	{
		$this->webClient = $webClient;
	}

	/**
	 * @throws RequestsDailyLimit
	 * @throws TokenRequired
	 * @throws TokenIncorrectFormat
	 * @throws InvalidServerResponse
	 * @throws TokenNotFound
	 * @throws IpBlocked
	 */
	public function parse(string $lemma): DeclensionResult
	{
		if (trim($lemma) == '')
		{
			throw new InvalidArgumentEmptyString();
		}

		$query = ["s" => $lemma];

		try
		{
			$result_raw = $this->webClient->send("/qazaq/declension", $query);
		}
		catch (MorpherError $ex)
		{
			if ($ex->getCode() == 5)
			{
				throw new QazaqWordsNotFound($ex->getMessage());
			}

			throw new InvalidServerResponse("Неизвестный код ошибки");
		}

		$result = WebClient::jsonDecode($result_raw);

		$result['A'] = $lemma;

		return new DeclensionResult($result);
	}
}
