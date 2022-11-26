<?php

declare(strict_types=1);

require_once __DIR__ . "/../../vendor/autoload.php";
require_once __DIR__ . "/IntegrationBase.php";

final class QueriesLeftTest extends IntegrationBase
{
	/**
	 * @throws \Morpher\Ws3Client\Exceptions\RequestsDailyLimit
	 * @throws \Morpher\Ws3Client\Exceptions\TokenRequired
	 * @throws \Morpher\Ws3Client\Exceptions\TokenIncorrectFormat
	 * @throws \Morpher\Ws3Client\Exceptions\InvalidServerResponse
	 * @throws \Morpher\Ws3Client\Exceptions\TokenNotFound
	 * @throws \Morpher\Ws3Client\Exceptions\IpBlocked
	 */
	public function testQueriesLeft(): void
	{
		$c = self::$testMorpher->getQueriesLeftForToday();
		print "\r\n";
		print ($c . " queries left for today\r\n");
		$this->assertTrue($c > 0, "daily queries limit exceed, or incorrect response");
	}
}