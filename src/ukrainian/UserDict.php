<?php

namespace Morpher\Ws3Client\Ukrainian;

use Morpher\Ws3Client\Exceptions\InvalidServerResponse;
use Morpher\Ws3Client\Exceptions\IpBlocked;
use Morpher\Ws3Client\Exceptions\RequestsDailyLimit;
use Morpher\Ws3Client\Exceptions\TokenIncorrectFormat;
use Morpher\Ws3Client\Exceptions\TokenNotFound;
use Morpher\Ws3Client\Exceptions\TokenRequired;
use Morpher\Ws3Client\WebClient;
use Morpher\Ws3Client\UserDictBase;

class UserDict extends UserDictBase
{
	public function __construct(WebClient $webClient)
	{
		parent::__construct($webClient, '/ukrainian/userdict', CorrectionEntry::class);
	}

	/**
	 * @throws RequestsDailyLimit
	 * @throws TokenRequired
	 * @throws TokenIncorrectFormat
	 * @throws InvalidServerResponse
	 * @throws TokenNotFound
	 * @throws IpBlocked
	 */
	public function addOrUpdate(CorrectionEntry $entry): void
	{
		$this->addOrUpdateBase($entry);
	}
}
