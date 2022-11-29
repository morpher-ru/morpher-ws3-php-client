<?php declare(strict_types=1);
require_once __DIR__ . "/../../../vendor/autoload.php";

require_once __DIR__ . "/../MorpherTestHelper.php";

use PHPUnit\Framework\TestCase;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;
use Morpher\Ws3Client\Ukrainian\CorrectionEntry;
use Morpher\Ws3Client\WebClient;

final class UkrainianUserDictTest extends TestCase
{
	public function testUserDictGet_Success(): void
	{
		$parseResults = [
			[
				"singular" => [
					"Н" => "Кошка",
					"Р" => "Пантеры",
					"Д" => "Пантере",
					"З" => "Пантеру",
					"О" => "Пантерой",
					"М" => "о Пантере",
					"К" => "в Пантере"
				]
			]
		];

		$return_text = json_encode($parseResults, JSON_UNESCAPED_UNICODE);

		$container = [];

		$testMorpher = MorpherTestHelper::createMockMorpher($container, $return_text);

		$list = $testMorpher->ukrainian->userDict->GetAll();

		$transaction = reset($container);//get first element of requests history

		//check request parameters, headers, uri
		$request = $transaction['request'];

		$this->assertEquals('GET', $request->getMethod());

		$uri = $request->getUri();
		$this->assertEquals('/ukrainian/userdict', $uri->getPath());
		$this->assertEquals('test.uu', $uri->getHost());
		$this->assertEquals('', $uri->getQuery());

		$this->assertNotNull($list);
		$this->assertIsArray($list);
		$this->assertCount(1, $list);

		$this->assertInstanceOf(CorrectionEntry::class, $list[0]);
		$entry = $list[0];

		$this->assertEquals("Кошка", $entry->Singular->Nominative);
		$this->assertEquals("Пантеры", $entry->Singular->Genitive);
		$this->assertEquals("Пантере", $entry->Singular->Dative);
		$this->assertEquals("Пантеру", $entry->Singular->Accusative);
		$this->assertEquals("Пантерой", $entry->Singular->Instrumental);
		$this->assertEquals("о Пантере", $entry->Singular->Prepositional);
		$this->assertEquals("в Пантере", $entry->Singular->Vocative);
	}

	public function testUserDictRemove_Success(): void
	{
		$parseResults = [];

		$return_text = json_encode($parseResults, JSON_UNESCAPED_UNICODE);

		$container = [];

		$testMorpher = MorpherTestHelper::createMockMorpher($container, $return_text);

		$lemma = 'кошка';
		$testMorpher->ukrainian->userDict->Remove($lemma);

		$transaction = reset($container);//get first element of requests history

		//check request parameters, headers, uri
		$request = $transaction['request'];
		$this->assertEquals('DELETE', $request->getMethod());
		$uri = $request->getUri();
		$this->assertEquals('/ukrainian/userdict', $uri->getPath());
		$this->assertEquals('test.uu', $uri->getHost());
		$this->assertEquals('s=' . rawurlencode($lemma), $uri->getQuery());
	}

	public function testUserDictRemoveEmpty_Exception(): void
	{
		$this->expectException(\Morpher\Ws3Client\InvalidArgumentEmptyString::class);
		$this->expectExceptionMessage('Передана пустая строка.');

		$parseResults = [];

		$return_text = json_encode($parseResults, JSON_UNESCAPED_UNICODE);

		$container = [];

		$testMorpher = MorpherTestHelper::createMockMorpher($container, $return_text);

		$testMorpher->ukrainian->userDict->Remove('');
	}

	public function testUserDicPost_Success(): void
	{
		$parseResults = [];

		$return_text = json_encode($parseResults, JSON_UNESCAPED_UNICODE);

		$container = [];

		$testMorpher = MorpherTestHelper::createMockMorpher($container, $return_text);

		$correction = new CorrectionEntry();
		$correction->Singular->Nominative = 'чебуратор';
		$correction->Singular->Vocative = 'в чебураторе';

		$testMorpher->ukrainian->userDict->AddOrUpdate($correction);

		$transaction = reset($container);//get first element of requests history

		//check request parameters, headers, uri
		$request = $transaction['request'];
		$this->assertEquals('POST', $request->getMethod());
		$uri = $request->getUri();
		$this->assertEquals('/ukrainian/userdict', $uri->getPath());
		$this->assertEquals('test.uu', $uri->getHost());
		$this->assertEmpty($uri->getQuery());

		$this->assertEquals(urlencode('Н') .
		                    '=' .
		                    urlencode('чебуратор') .
		                    '&' .
		                    urlencode('К') .
		                    '=' .
		                    urlencode('в чебураторе'), (string)$request->getBody());
	}

	public function testUserDicPost_Exception(): void
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('Обязательно должен быть указан именительный падеж единственного числа.');

		$parseResults = [];

		$return_text = json_encode($parseResults, JSON_UNESCAPED_UNICODE);

		$container = [];

		$testMorpher = MorpherTestHelper::createMockMorpher($container, $return_text);

		$correction = new CorrectionEntry();
		//$correction->Singular->Nominative='чебуратор';
		$correction->Singular->Vocative = 'в чебураторе';
		//$correction->Plural->Vocative='в чебураторах';

		$testMorpher->ukrainian->userDict->AddOrUpdate($correction);
	}

	public function testUserDicPost_Exception2(): void
	{
		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('Нужно указать хотя бы одну косвенную форму.');

		$parseResults = [];

		$return_text = json_encode($parseResults, JSON_UNESCAPED_UNICODE);

		$container = [];

		$testMorpher = MorpherTestHelper::createMockMorpher($container, $return_text);

		$correction = new CorrectionEntry();
		$correction->Singular->Nominative = 'чебуратор';
		//$correction->Singular->Vocative='в чебураторе';
		//$correction->Plural->Vocative='в чебураторах';

		$lresult = $testMorpher->ukrainian->userDict->AddOrUpdate($correction);
	}
}
