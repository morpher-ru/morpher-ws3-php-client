<?php declare(strict_types=1);
require_once __DIR__ . "/../../../vendor/autoload.php";

require_once __DIR__ . "/../MorpherTestHelper.php";

use PHPUnit\Framework\TestCase;

use Morpher\Ws3Client\Russian as Russian;

final class RussianAdjectiveGendersTest extends TestCase
{
	public function testSpell_Success(): void
	{
		$parseResults = [
			"feminine" => "уважаемая",
			"neuter" => "уважаемое",
			"plural" => "уважаемые"
		];

		$return_text = json_encode($parseResults, JSON_UNESCAPED_UNICODE);

		$adj = "уважаемый";

		$container = [];

		$testMorpher = MorpherTestHelper::createMockMorpher($container, $return_text);

		$adjectiveGenders = $testMorpher->russian->AdjectiveGenders($adj);

		$transaction = reset($container);//get first element of requests history

		//check request parameters, headers, uri
		$request = $transaction['request'];

		$uri = $request->getUri();
		$this->assertEquals('/russian/genders', $uri->getPath());
		$this->assertEquals('test.uu', $uri->getHost());
		$this->assertEquals('s=' . rawurlencode($adj), $uri->getQuery());

		$this->assertNotNull($adjectiveGenders);
		$this->assertInstanceOf(Russian\AdjectiveGenders::class, $adjectiveGenders);

		$this->assertEquals("уважаемая", $adjectiveGenders->Feminine);
		$this->assertEquals("уважаемое", $adjectiveGenders->Neuter);
		$this->assertEquals("уважаемые", $adjectiveGenders->Plural);
	}

	public function testAdjectiveGenders_error(): void
	{
		$this->expectException(Russian\AdjectiveFormIncorrect::class);

		$parseResults = [
			"feminine" => "ERROR",
			"neuter" => "ERROR",
			"plural" => "ERROR"
		];

		$return_text = json_encode($parseResults, JSON_UNESCAPED_UNICODE);

		$adj = "уважаемого";

		$container = [];

		$testMorpher = MorpherTestHelper::createMockMorpher($container, $return_text);

		$adjectiveGenders = $testMorpher->russian->AdjectiveGenders($adj);
	}

	public function testAdjectiveGenders_Empty(): void
	{
		$this->expectException(\Morpher\Ws3Client\InvalidArgumentEmptyString::class);

		$container = [];

		$testMorpher = MorpherTestHelper::createMockMorpher($container, '');

		$declensionResult = $testMorpher->russian->AdjectiveGenders('   ');
	}
}