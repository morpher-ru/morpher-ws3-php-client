<?php

namespace Morpher\Ws3Client;

use Morpher\Ws3Client\Exceptions\InvalidServerResponse;
use Morpher\Ws3Client\Exceptions\IpBlocked;
use Morpher\Ws3Client\Exceptions\MorpherError;
use Morpher\Ws3Client\Exceptions\RequestsDailyLimit;
use Morpher\Ws3Client\Exceptions\TokenIncorrectFormat;
use Morpher\Ws3Client\Exceptions\TokenNotFound;
use Morpher\Ws3Client\Exceptions\TokenRequired;
use Morpher\Ws3Client\Russian\Client as RussianClient;
use Morpher\Ws3Client\Qazaq\Client as QazaqClient;
use Morpher\Ws3Client\Ukrainian\Client as UkrainianClient;

class Morpher
{
	/**
	 * @readonly
	 */
	public RussianClient $russian;
	/**
	 * @readonly
	 */
	public QazaqClient $qazaq;
	/**
	 * @readonly
	 */
	public UkrainianClient $ukrainian;

	/**
	 * @readonly
	 */
	private WebClient $_webClient;

	public function __construct(string $url = 'https://ws3.morpher.ru', string $token = '', float $timeout = 10.0,
		$handler = null)
	{
		$this->_webClient = new WebClient($url, $token, $timeout, $handler);
		$this->russian = new RussianClient($this->_webClient);
		$this->qazaq = new QazaqClient($this->_webClient);
		$this->ukrainian = new UkrainianClient($this->_webClient);
	}

	/**
	 * @throws RequestsDailyLimit
	 * @throws TokenRequired
	 * @throws TokenIncorrectFormat
	 * @throws InvalidServerResponse
	 * @throws TokenNotFound
	 * @throws IpBlocked
	 */
	public function getQueriesLeftForToday(): int
	{
		try
		{
			$result_raw = $this->_webClient->send("/get_queries_left_for_today");
		}
		catch (MorpherError $ex)
		{
			throw new InvalidServerResponse("Неизвестный код ошибки");
		}

		$result = WebClient::jsonDecode($result_raw);

		return (int)$result;
	}
}
