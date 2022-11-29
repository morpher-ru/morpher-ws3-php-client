<?php declare(strict_types=1);
require_once __DIR__ . "/../../../vendor/autoload.php";

require_once __DIR__ . "/../IntegrationBase.php";

use PHPUnit\Framework\TestCase;

use Morpher\Ws3Client\WebClient;
use Morpher\Ws3Client\Morpher;

use Morpher\Ws3Client\Russian as Russian;

final class RussianSpellDateTest extends IntegrationBase
{
	public function SpellDateProvider(): array
	{
		return [["2019-06-29"], [new \DateTime('29.06.2019')], [(new \DateTime('29.06.2019'))->getTimestamp()]];

	}

	/**
	 * @dataProvider  SpellDateProvider
	 */
	public function testSpellDate_Success($date): void
	{


		$dateSpellingResult = self::$testMorpher->russian->SpellDate($date);

		$this->assertInstanceOf(Russian\DateSpellingResult::class, $dateSpellingResult);

		$this->assertNotNull($dateSpellingResult);

		$this->assertEquals("двадцать девятое июня две тысячи девятнадцатого года", $dateSpellingResult->Nominative);
		$this->assertEquals("двадцать девятого июня две тысячи девятнадцатого года", $dateSpellingResult->Genitive);
		$this->assertEquals("двадцать девятому июня две тысячи девятнадцатого года", $dateSpellingResult->Dative);
		$this->assertEquals("двадцать девятое июня две тысячи девятнадцатого года", $dateSpellingResult->Accusative);
		$this->assertEquals("двадцать девятым июня две тысячи девятнадцатого года", $dateSpellingResult->Instrumental);
		$this->assertEquals("двадцать девятом июня две тысячи девятнадцатого года", $dateSpellingResult->Prepositional);
	}

	public function testSpellDate_Exception(): void
	{
		$this->expectException(InvalidArgumentException::class);

		self::$testMorpher->russian->SpellDate(null);
	}

	public function testSpellDate_Empty(): void
	{
		$this->expectException(\Morpher\Ws3Client\InvalidArgumentEmptyString::class);

		self::$testMorpher->russian->SpellDate("   ");
	}

	public function testSpellDate_IncorrectFormat(): void
	{
		$this->expectException(Russian\IncorrectDateFormat::class);

		self::$testMorpher->russian->SpellDate("2022.10.01");
	}

}