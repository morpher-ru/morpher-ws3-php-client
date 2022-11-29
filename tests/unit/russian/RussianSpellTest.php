<?php declare(strict_types=1);
require_once __DIR__ . "/../../../vendor/autoload.php";

require_once __DIR__ . "/../MorpherTestHelper.php";

use PHPUnit\Framework\TestCase;

use Morpher\Ws3Client\Russian as Russian;

final class RussianSpellTest extends TestCase
{
	public function testSpell_Success(): void
	{
		$parseResults = [
			"n" => [
				"И" => "десять",
				"Р" => "десяти",
				"Д" => "десяти",
				"В" => "десять",
				"Т" => "десятью",
				"П" => "десяти"
			],
			"unit" => [
				"И" => "рублей",
				"Р" => "рублей",
				"Д" => "рублям",
				"В" => "рублей",
				"Т" => "рублями",
				"П" => "рублях"
			]
		];

		$return_text = json_encode($parseResults, JSON_UNESCAPED_UNICODE);

		$unit = "рубль";

		$container = [];

		$testMorpher = MorpherTestHelper::createMockMorpher($container, $return_text);

		$spellingResult = $testMorpher->russian->Spell(10, $unit);

		$transaction = reset($container);//get first element of requests history

		//check request parameters, headers, uri
		$request = $transaction['request'];

		$uri = $request->getUri();
		$this->assertEquals('/russian/spell', $uri->getPath());
		$this->assertEquals('test.uu', $uri->getHost());
		$this->assertEquals('n=10&unit=' . rawurlencode($unit), $uri->getQuery());

		$this->assertInstanceOf(Russian\NumberSpellingResult::class, $spellingResult);

		$this->assertNotNull($spellingResult);

		// number
		$this->assertEquals("десять", $spellingResult->NumberDeclension->Nominative);
		$this->assertEquals("десяти", $spellingResult->NumberDeclension->Genitive);
		$this->assertEquals("десяти", $spellingResult->NumberDeclension->Dative);
		$this->assertEquals("десять", $spellingResult->NumberDeclension->Accusative);
		$this->assertEquals("десятью", $spellingResult->NumberDeclension->Instrumental);
		$this->assertEquals("десяти", $spellingResult->NumberDeclension->Prepositional);

		// unit
		$this->assertEquals("рублей", $spellingResult->UnitDeclension->Nominative);
		$this->assertEquals("рублей", $spellingResult->UnitDeclension->Genitive);
		$this->assertEquals("рублям", $spellingResult->UnitDeclension->Dative);
		$this->assertEquals("рублей", $spellingResult->UnitDeclension->Accusative);
		$this->assertEquals("рублями", $spellingResult->UnitDeclension->Instrumental);
		$this->assertEquals("рублях", $spellingResult->UnitDeclension->Prepositional);
	}

	public function testSpell_Empty(): void
	{
		$this->expectException(\Morpher\Ws3Client\InvalidArgumentEmptyString::class);

		$container = [];

		$testMorpher = MorpherTestHelper::createMockMorpher($container, '');

		$declensionResult = $testMorpher->russian->Spell(1, '   ');
	}
}
