<?php

namespace Morpher\Ws3Client;

use InvalidArgumentException;
use Morpher\Ws3Client\Exceptions\InvalidArgumentEmptyString;
use Morpher\Ws3Client\Exceptions\InvalidServerResponse;
use Morpher\Ws3Client\Exceptions\IpBlocked;
use Morpher\Ws3Client\Exceptions\MorpherError;
use Morpher\Ws3Client\Exceptions\RequestsDailyLimit;
use Morpher\Ws3Client\Exceptions\TokenIncorrectFormat;
use Morpher\Ws3Client\Exceptions\TokenNotFound;
use Morpher\Ws3Client\Exceptions\TokenRequired;

abstract class UserDictBase
{
	/**
	 * @readonly
	 */
	protected WebClient $webClient;

	/**
	 * @readonly
	 */
	protected string $endpoint;

	/**
	 * @readonly
	 */
	protected string $correctionEntryClassName;

	public function __construct(WebClient $webClient, string $endpoint, string $correctionEntryClassName)
	{
		$this->webClient = $webClient;
		$this->endpoint = $endpoint;
		$this->correctionEntryClassName = $correctionEntryClassName;
	}

	/**
	 * @throws RequestsDailyLimit
	 * @throws TokenRequired
	 * @throws TokenIncorrectFormat
	 * @throws InvalidServerResponse
	 * @throws TokenNotFound
	 * @throws IpBlocked
	 */
	public function remove(string $nominativeForm): void
	{
		if (empty(trim($nominativeForm)))
		{
			throw new InvalidArgumentEmptyString();
		}

		$queryParam = ["s" => $nominativeForm];

		try
		{
			$this->webClient->send($this->endpoint, $queryParam, 'DELETE');
		}
		catch (MorpherError $ex)
		{
			throw new InvalidServerResponse("Неизвестный код ошибки");
		}
	}

	/**
	 * @throws TokenRequired
	 * @throws RequestsDailyLimit
	 * @throws TokenIncorrectFormat
	 * @throws InvalidServerResponse
	 * @throws TokenNotFound
	 * @throws IpBlocked
	 */
	public function getAll(): array
	{
		try
		{
			$result_raw = $this->webClient->send($this->endpoint);
		}
		catch (MorpherError $ex)
		{
			throw new InvalidServerResponse("Неизвестный код ошибки");
		}

		$result = WebClient::JsonDecode($result_raw);

		return array_map(fn (array $item) => new $this->correctionEntryClassName($item), $result);
	}

	/**
	 * @throws TokenRequired
	 * @throws RequestsDailyLimit
	 * @throws InvalidServerResponse
	 * @throws TokenIncorrectFormat
	 * @throws TokenNotFound
	 * @throws IpBlocked
	 */
	protected function addOrUpdateBase(CorrectionEntryInterface $entry): void
	{
		if (!($entry instanceof $this->correctionEntryClassName))
		{
			throw new InvalidArgumentException("$entry не является экземпляром подходящего класса.");
		}

		if (!$entry->singularNominativeExists())
		{
			throw new InvalidArgumentException("Обязательно должен быть указан именительный падеж единственного числа.");
		}

		$formParam = $entry->getArrayForRequest();

		if (count($formParam) < 2)
		{
			throw new InvalidArgumentException("Нужно указать хотя бы одну косвенную форму.");
		}

		try
		{
			$this->webClient->send($this->endpoint, [], 'POST', null, null, $formParam);
		}
		catch (MorpherError $ex)
		{
			// todo: проверить ошибку 6

			throw new InvalidServerResponse("Неизвестный код ошибки");
		}
	}
}
