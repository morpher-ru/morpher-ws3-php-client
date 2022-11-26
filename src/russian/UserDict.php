<?php

namespace Morpher\Ws3Client\Russian;

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
		parent::__construct($webClient, '/russian/userdict', CorrectionEntry::class);
	}

	/**
	 * @throws TokenRequired
	 * @throws RequestsDailyLimit
	 * @throws InvalidServerResponse
	 * @throws TokenIncorrectFormat
	 * @throws TokenNotFound
	 * @throws IpBlocked
	 */
	public function addOrUpdate(CorrectionEntry $entry): void
	{
		$this->addOrUpdateBase($entry);
	}
}
