<?php

declare(strict_types=1);

require_once __DIR__ . "/../../../vendor/autoload.php";
require_once __DIR__ . "/../MorpherTestHelper.php";

use Morpher\Ws3Client\Exceptions\InvalidArgumentEmptyString;
use PHPUnit\Framework\TestCase;

use Morpher\Ws3Client\Qazaq as Qazaq;

final class QazaqDeclensionTest extends TestCase
{
	/**
	 * @throws \Morpher\Ws3Client\Exceptions\RequestsDailyLimit
	 * @throws \Morpher\Ws3Client\Exceptions\TokenRequired
	 * @throws \Morpher\Ws3Client\Exceptions\TokenIncorrectFormat
	 * @throws \Morpher\Ws3Client\Exceptions\InvalidServerResponse
	 * @throws \Morpher\Ws3Client\Exceptions\TokenNotFound
	 * @throws \Morpher\Ws3Client\Exceptions\IpBlocked
	 */
	public function testQazaqParse_Success(): void
	{
		$parseResults = [
			"І" => "тесттің",
			"Б" => "тестке",
			"Т" => "тестті",
			"Ш" => "тесттен",
			"Ж" => "тестте",
			"К" => "тестпен",
			"көпше" => [
				"A" => "тесттер",
				"І" => "тесттертің",
				"Б" => "тесттерке",
				"Т" => "тесттерті",
				"Ш" => "тесттертен",
				"Ж" => "тесттерте",
				"К" => "тесттерпен"
			]
		];

		$return_text = json_encode($parseResults, JSON_UNESCAPED_UNICODE);

		$lemma = 'тест';

		$container = [];

		$testMorpher = MorpherTestHelper::createMockMorpher($container, $return_text);

		$declensionResult = $testMorpher->qazaq->parse($lemma);

		$transaction = reset($container); // get first element of requests history

		//check request parameters, headers, uri
		$request = $transaction['request'];

		$uri = $request->getUri();
		$this->assertEquals('/qazaq/declension', $uri->getPath());
		$this->assertEquals('test.uu', $uri->getHost());
		$this->assertEquals('s=' . rawurlencode($lemma), $uri->getQuery());

		$this->assertInstanceOf(Qazaq\DeclensionForms::class, $declensionResult);
		$this->assertInstanceOf(Qazaq\SameNumberForms::class, $declensionResult->plural);

		$this->assertEquals("тест", $declensionResult->nominative);
		$this->assertEquals("тесттің", $declensionResult->genitive);
		$this->assertEquals("тестке", $declensionResult->dative);
		$this->assertEquals("тестті", $declensionResult->accusative);
		$this->assertEquals("тесттен", $declensionResult->ablative);
		$this->assertEquals("тестте", $declensionResult->locative);
		$this->assertEquals("тестпен", $declensionResult->instrumental);

		$this->assertEquals("тесттер", $declensionResult->plural->nominative);
		$this->assertEquals("тесттертің", $declensionResult->plural->genitive);
		$this->assertEquals("тесттерке", $declensionResult->plural->dative);
		$this->assertEquals("тесттерті", $declensionResult->plural->accusative);
		$this->assertEquals("тесттертен", $declensionResult->plural->ablative);
		$this->assertEquals("тесттерте", $declensionResult->plural->locative);
		$this->assertEquals("тесттерпен", $declensionResult->plural->instrumental);
	}

	/**
	 * @throws \Morpher\Ws3Client\Exceptions\TokenRequired
	 * @throws \Morpher\Ws3Client\Exceptions\RequestsDailyLimit
	 * @throws \Morpher\Ws3Client\Exceptions\TokenIncorrectFormat
	 * @throws \Morpher\Ws3Client\Exceptions\InvalidServerResponse
	 * @throws \Morpher\Ws3Client\Exceptions\TokenNotFound
	 * @throws \Morpher\Ws3Client\Exceptions\IpBlocked
	 */
	public function testQazaqParse_Personal_Success(): void
	{
		$parseResults = [
			"A" => "бала",
			"І" => "баланың",
			"Б" => "балаға",
			"Т" => "баланы",
			"Ш" => "баладан",
			"Ж" => "балада",
			"К" => "баламен",
			"менің" => [
				"A" => "балам",
				"І" => "баламның",
				"Б" => "балама",
				"Т" => "баламды",
				"Ш" => "баламнан",
				"Ж" => "баламда",
				"К" => "баламмен"
			],
			"сенің" => [
				"A" => "балаң",
				"І" => "балаңның",
				"Б" => "балаңа",
				"Т" => "балаңды",
				"Ш" => "балаңнан",
				"Ж" => "балаңда",
				"К" => "балаңмен"
			],
			"сіздің" => [
				"A" => "балаңыз",
				"І" => "балаңыздың",
				"Б" => "балаңызға",
				"Т" => "балаңызды",
				"Ш" => "балаңыздан",
				"Ж" => "балаңызда",
				"К" => "балаңызбен"
			],
			"оның" => [
				"A" => "баласы",
				"І" => "баласының",
				"Б" => "баласына",
				"Т" => "баласын",
				"Ш" => "баласынан",
				"Ж" => "баласында",
				"К" => "баласымен"
			],
			"біздің" => [
				"A" => "баламыз",
				"І" => "баламыздың",
				"Б" => "баламызға",
				"Т" => "баламызды",
				"Ш" => "баламыздан",
				"Ж" => "баламызда",
				"К" => "баламызбен"
			],
			"сендердің" => [
				"A" => "балаларың",
				"І" => "балаларыңның",
				"Б" => "балаларыңа",
				"Т" => "балаларыңды",
				"Ш" => "балаларыңнан",
				"Ж" => "балаларыңда",
				"К" => "балаларыңмен"
			],
			"сіздердің" => [
				"A" => "балаларыңыз",
				"І" => "балаларыңыздың",
				"Б" => "балаларыңызға",
				"Т" => "балаларыңызды",
				"Ш" => "балаларыңыздан",
				"Ж" => "балаларыңызда",
				"К" => "балаларыңызбен"
			],
			"олардың" => [
				"A" => "балалары",
				"І" => "балаларының",
				"Б" => "балаларына",
				"Т" => "балаларын",
				"Ш" => "балаларынан",
				"Ж" => "балаларында",
				"К" => "балаларымен"
			],
			"көпше" => [
				"A" => "балалар",
				"І" => "балалардың",
				"Б" => "балаларға",
				"Т" => "балаларды",
				"Ш" => "балалардан",
				"Ж" => "балаларда",
				"К" => "балалармен",
				"менің" => [
					"A" => "балаларым",
					"І" => "балаларымның",
					"Б" => "балаларыма",
					"Т" => "балаларымды",
					"Ш" => "балаларымнан",
					"Ж" => "балаларымда",
					"К" => "балаларыммен"
				],
				"сенің" => [
					"A" => "балаларың",
					"І" => "балаларыңның",
					"Б" => "балаларыңа",
					"Т" => "балаларыңды",
					"Ш" => "балаларыңнан",
					"Ж" => "балаларыңда",
					"К" => "балаларыңмен"
				],
				"сіздің" => [
					"A" => "балаларыңыз",
					"І" => "балаларыңыздың",
					"Б" => "балаларыңызға",
					"Т" => "балаларыңызды",
					"Ш" => "балаларыңыздан",
					"Ж" => "балаларыңызда",
					"К" => "балаларыңызбен"
				],
				"оның" => [
					"A" => "балалары",
					"І" => "балаларының",
					"Б" => "балаларына",
					"Т" => "балаларын",
					"Ш" => "балаларынан",
					"Ж" => "балаларында",
					"К" => "балаларымен"
				],
				"біздің" => [
					"A" => "балаларымыз",
					"І" => "балаларымыздың",
					"Б" => "балаларымызға",
					"Т" => "балаларымызды",
					"Ш" => "балаларымыздан",
					"Ж" => "балаларымызда",
					"К" => "балаларымызбен"
				],
				"сендердің" => [
					"A" => "балаларың",
					"І" => "балаларыңның",
					"Б" => "балаларыңа",
					"Т" => "балаларыңды",
					"Ш" => "балаларыңнан",
					"Ж" => "балаларыңда",
					"К" => "балаларыңмен"
				],
				"сіздердің" => [
					"A" => "балаларыңыз",
					"І" => "балаларыңыздың",
					"Б" => "балаларыңызға",
					"Т" => "балаларыңызды",
					"Ш" => "балаларыңыздан",
					"Ж" => "балаларыңызда",
					"К" => "балаларыңызбен"
				],
				"олардың" => [
					"A" => "балалары",
					"І" => "балаларының",
					"Б" => "балаларына",
					"Т" => "балаларын",
					"Ш" => "балаларынан",
					"Ж" => "балаларында",
					"К" => "балаларымен"
				]
			]
		];

		$return_text = json_encode($parseResults, JSON_UNESCAPED_UNICODE);

		$lemma = 'бала';

		$container = [];

		$testMorpher = MorpherTestHelper::createMockMorpher($container, $return_text);

		$declensionResult = $testMorpher->qazaq->parse($lemma);

		$transaction = reset($container);//get first element of requests history

		//check request parameters, headers, uri
		$request = $transaction['request'];

		$uri = $request->getUri();
		$this->assertEquals('/qazaq/declension', $uri->getPath());
		$this->assertEquals('test.uu', $uri->getHost());
		$this->assertEquals('s=' . rawurlencode($lemma), $uri->getQuery());

		$this->assertInstanceOf(Qazaq\DeclensionForms::class, $declensionResult);
		$this->assertInstanceOf(Qazaq\SameNumberForms::class, $declensionResult->plural);

		$this->assertNotNull($declensionResult);
		$this->assertEquals("бала", $declensionResult->nominative);
		$this->assertEquals("баланың", $declensionResult->genitive);
		$this->assertEquals("балаға", $declensionResult->dative);
		$this->assertEquals("баланы", $declensionResult->accusative);
		$this->assertEquals("баладан", $declensionResult->ablative);
		$this->assertEquals("балада", $declensionResult->locative);
		$this->assertEquals("баламен", $declensionResult->instrumental);

		$this->assertNotNull($declensionResult->firstPerson);
		$this->assertEquals("балам", $declensionResult->firstPerson->nominative);
		$this->assertEquals("баламның", $declensionResult->firstPerson->genitive);
		$this->assertEquals("балама", $declensionResult->firstPerson->dative);
		$this->assertEquals("баламды", $declensionResult->firstPerson->accusative);
		$this->assertEquals("баламнан", $declensionResult->firstPerson->ablative);
		$this->assertEquals("баламда", $declensionResult->firstPerson->locative);
		$this->assertEquals("баламмен", $declensionResult->firstPerson->instrumental);

		$this->assertNotNull($declensionResult->secondPerson);
		$this->assertEquals("балаң", $declensionResult->secondPerson->nominative);
		$this->assertEquals("балаңның", $declensionResult->secondPerson->genitive);
		$this->assertEquals("балаңа", $declensionResult->secondPerson->dative);
		$this->assertEquals("балаңды", $declensionResult->secondPerson->accusative);
		$this->assertEquals("балаңнан", $declensionResult->secondPerson->ablative);
		$this->assertEquals("балаңда", $declensionResult->secondPerson->locative);
		$this->assertEquals("балаңмен", $declensionResult->secondPerson->instrumental);

		$this->assertNotNull($declensionResult->secondPersonRespectful);
		$this->assertEquals("балаңыз", $declensionResult->secondPersonRespectful->nominative);
		$this->assertEquals("балаңыздың", $declensionResult->secondPersonRespectful->genitive);
		$this->assertEquals("балаңызға", $declensionResult->secondPersonRespectful->dative);
		$this->assertEquals("балаңызды", $declensionResult->secondPersonRespectful->accusative);
		$this->assertEquals("балаңыздан", $declensionResult->secondPersonRespectful->ablative);
		$this->assertEquals("балаңызда", $declensionResult->secondPersonRespectful->locative);
		$this->assertEquals("балаңызбен", $declensionResult->secondPersonRespectful->instrumental);

		$this->assertNotNull($declensionResult->thirdPerson);
		$this->assertEquals("баласы", $declensionResult->thirdPerson->nominative);
		$this->assertEquals("баласының", $declensionResult->thirdPerson->genitive);
		$this->assertEquals("баласына", $declensionResult->thirdPerson->dative);
		$this->assertEquals("баласын", $declensionResult->thirdPerson->accusative);
		$this->assertEquals("баласынан", $declensionResult->thirdPerson->ablative);
		$this->assertEquals("баласында", $declensionResult->thirdPerson->locative);
		$this->assertEquals("баласымен", $declensionResult->thirdPerson->instrumental);

		$this->assertNotNull($declensionResult->firstPersonPlural);
		$this->assertEquals("баламыз", $declensionResult->firstPersonPlural->nominative);
		$this->assertEquals("баламыздың", $declensionResult->firstPersonPlural->genitive);
		$this->assertEquals("баламызға", $declensionResult->firstPersonPlural->dative);
		$this->assertEquals("баламызды", $declensionResult->firstPersonPlural->accusative);
		$this->assertEquals("баламыздан", $declensionResult->firstPersonPlural->ablative);
		$this->assertEquals("баламызда", $declensionResult->firstPersonPlural->locative);
		$this->assertEquals("баламызбен", $declensionResult->firstPersonPlural->instrumental);

		$this->assertNotNull($declensionResult->secondPerson);
		$this->assertEquals("балаң", $declensionResult->secondPerson->nominative);
		$this->assertEquals("балаңның", $declensionResult->secondPerson->genitive);
		$this->assertEquals("балаңа", $declensionResult->secondPerson->dative);
		$this->assertEquals("балаңды", $declensionResult->secondPerson->accusative);
		$this->assertEquals("балаңнан", $declensionResult->secondPerson->ablative);
		$this->assertEquals("балаңда", $declensionResult->secondPerson->locative);
		$this->assertEquals("балаңмен", $declensionResult->secondPerson->instrumental);

		$this->assertNotNull($declensionResult->secondPersonRespectful);
		$this->assertEquals("балаңыз", $declensionResult->secondPersonRespectful->nominative);
		$this->assertEquals("балаңыздың", $declensionResult->secondPersonRespectful->genitive);
		$this->assertEquals("балаңызға", $declensionResult->secondPersonRespectful->dative);
		$this->assertEquals("балаңызды", $declensionResult->secondPersonRespectful->accusative);
		$this->assertEquals("балаңыздан", $declensionResult->secondPersonRespectful->ablative);
		$this->assertEquals("балаңызда", $declensionResult->secondPersonRespectful->locative);
		$this->assertEquals("балаңызбен", $declensionResult->secondPersonRespectful->instrumental);

		$this->assertNotNull($declensionResult->thirdPersonPlural);
		$this->assertEquals("балалары", $declensionResult->thirdPersonPlural->nominative);
		$this->assertEquals("балаларының", $declensionResult->thirdPersonPlural->genitive);
		$this->assertEquals("балаларына", $declensionResult->thirdPersonPlural->dative);
		$this->assertEquals("балаларын", $declensionResult->thirdPersonPlural->accusative);
		$this->assertEquals("балаларынан", $declensionResult->thirdPersonPlural->ablative);
		$this->assertEquals("балаларында", $declensionResult->thirdPersonPlural->locative);
		$this->assertEquals("балаларымен", $declensionResult->thirdPersonPlural->instrumental);

		$this->assertNotNull($declensionResult->plural);
		$this->assertEquals("балалар", $declensionResult->plural->nominative);
		$this->assertEquals("балалардың", $declensionResult->plural->genitive);
		$this->assertEquals("балаларға", $declensionResult->plural->dative);
		$this->assertEquals("балаларды", $declensionResult->plural->accusative);
		$this->assertEquals("балалардан", $declensionResult->plural->ablative);
		$this->assertEquals("балаларда", $declensionResult->plural->locative);
		$this->assertEquals("балалармен", $declensionResult->plural->instrumental);

		$this->assertNotNull($declensionResult->plural->firstPerson);
		$this->assertEquals("балаларым", $declensionResult->plural->firstPerson->nominative);
		$this->assertEquals("балаларымның", $declensionResult->plural->firstPerson->genitive);
		$this->assertEquals("балаларыма", $declensionResult->plural->firstPerson->dative);
		$this->assertEquals("балаларымды", $declensionResult->plural->firstPerson->accusative);
		$this->assertEquals("балаларымнан", $declensionResult->plural->firstPerson->ablative);
		$this->assertEquals("балаларымда", $declensionResult->plural->firstPerson->locative);
		$this->assertEquals("балаларыммен", $declensionResult->plural->firstPerson->instrumental);

		$this->assertNotNull($declensionResult->plural->secondPerson);
		$this->assertEquals("балаларың", $declensionResult->plural->secondPerson->nominative);
		$this->assertEquals("балаларыңның", $declensionResult->plural->secondPerson->genitive);
		$this->assertEquals("балаларыңа", $declensionResult->plural->secondPerson->dative);
		$this->assertEquals("балаларыңды", $declensionResult->plural->secondPerson->accusative);
		$this->assertEquals("балаларыңнан", $declensionResult->plural->secondPerson->ablative);
		$this->assertEquals("балаларыңда", $declensionResult->plural->secondPerson->locative);
		$this->assertEquals("балаларыңмен", $declensionResult->plural->secondPerson->instrumental);

		$this->assertNotNull($declensionResult->plural->secondPersonRespectful);
		$this->assertEquals("балаларыңыз", $declensionResult->plural->secondPersonRespectful->nominative);
		$this->assertEquals("балаларыңыздың", $declensionResult->plural->secondPersonRespectful->genitive);
		$this->assertEquals("балаларыңызға", $declensionResult->plural->secondPersonRespectful->dative);
		$this->assertEquals("балаларыңызды", $declensionResult->plural->secondPersonRespectful->accusative);
		$this->assertEquals("балаларыңыздан", $declensionResult->plural->secondPersonRespectful->ablative);
		$this->assertEquals("балаларыңызда", $declensionResult->plural->secondPersonRespectful->locative);
		$this->assertEquals("балаларыңызбен", $declensionResult->plural->secondPersonRespectful->instrumental);

		$this->assertNotNull($declensionResult->plural->thirdPerson);
		$this->assertEquals("балалары", $declensionResult->plural->thirdPerson->nominative);
		$this->assertEquals("балаларының", $declensionResult->plural->thirdPerson->genitive);
		$this->assertEquals("балаларына", $declensionResult->plural->thirdPerson->dative);
		$this->assertEquals("балаларын", $declensionResult->plural->thirdPerson->accusative);
		$this->assertEquals("балаларынан", $declensionResult->plural->thirdPerson->ablative);
		$this->assertEquals("балаларында", $declensionResult->plural->thirdPerson->locative);
		$this->assertEquals("балаларымен", $declensionResult->plural->thirdPerson->instrumental);

		$this->assertNotNull($declensionResult->plural->firstPersonPlural);
		$this->assertEquals("балаларымыз", $declensionResult->plural->firstPersonPlural->nominative);
		$this->assertEquals("балаларымыздың", $declensionResult->plural->firstPersonPlural->genitive);
		$this->assertEquals("балаларымызға", $declensionResult->plural->firstPersonPlural->dative);
		$this->assertEquals("балаларымызды", $declensionResult->plural->firstPersonPlural->accusative);
		$this->assertEquals("балаларымыздан", $declensionResult->plural->firstPersonPlural->ablative);
		$this->assertEquals("балаларымызда", $declensionResult->plural->firstPersonPlural->locative);
		$this->assertEquals("балаларымызбен", $declensionResult->plural->firstPersonPlural->instrumental);

		$this->assertNotNull($declensionResult->plural->secondPerson);
		$this->assertEquals("балаларың", $declensionResult->plural->secondPerson->nominative);
		$this->assertEquals("балаларыңның", $declensionResult->plural->secondPerson->genitive);
		$this->assertEquals("балаларыңа", $declensionResult->plural->secondPerson->dative);
		$this->assertEquals("балаларыңды", $declensionResult->plural->secondPerson->accusative);
		$this->assertEquals("балаларыңнан", $declensionResult->plural->secondPerson->ablative);
		$this->assertEquals("балаларыңда", $declensionResult->plural->secondPerson->locative);
		$this->assertEquals("балаларыңмен", $declensionResult->plural->secondPerson->instrumental);

		$this->assertNotNull($declensionResult->plural->secondPersonRespectful);
		$this->assertEquals("балаларыңыз", $declensionResult->plural->secondPersonRespectful->nominative);
		$this->assertEquals("балаларыңыздың", $declensionResult->plural->secondPersonRespectful->genitive);
		$this->assertEquals("балаларыңызға", $declensionResult->plural->secondPersonRespectful->dative);
		$this->assertEquals("балаларыңызды", $declensionResult->plural->secondPersonRespectful->accusative);
		$this->assertEquals("балаларыңыздан", $declensionResult->plural->secondPersonRespectful->ablative);
		$this->assertEquals("балаларыңызда", $declensionResult->plural->secondPersonRespectful->locative);
		$this->assertEquals("балаларыңызбен", $declensionResult->plural->secondPersonRespectful->instrumental);

		$this->assertNotNull($declensionResult->plural->thirdPersonPlural);
		$this->assertEquals("балалары", $declensionResult->plural->thirdPersonPlural->nominative);
		$this->assertEquals("балаларының", $declensionResult->plural->thirdPersonPlural->genitive);
		$this->assertEquals("балаларына", $declensionResult->plural->thirdPersonPlural->dative);
		$this->assertEquals("балаларын", $declensionResult->plural->thirdPersonPlural->accusative);
		$this->assertEquals("балаларынан", $declensionResult->plural->thirdPersonPlural->ablative);
		$this->assertEquals("балаларында", $declensionResult->plural->thirdPersonPlural->locative);
		$this->assertEquals("балаларымен", $declensionResult->plural->thirdPersonPlural->instrumental);
	}

	/**
	 * @throws \Morpher\Ws3Client\Exceptions\TokenRequired
	 * @throws \Morpher\Ws3Client\Exceptions\RequestsDailyLimit
	 * @throws \Morpher\Ws3Client\Exceptions\InvalidServerResponse
	 * @throws \Morpher\Ws3Client\Exceptions\TokenIncorrectFormat
	 * @throws \Morpher\Ws3Client\Exceptions\TokenNotFound
	 * @throws \Morpher\Ws3Client\Exceptions\IpBlocked
	 */
	public function testParse_ExceptionNoWords(): void
	{
		$this->expectException(Qazaq\Exceptions\QazaqWordsNotFound::class);
		$this->expectExceptionMessage('Не найдено казахских слов.');

		$parseResults = [
			'code' => 5,
			'message' => 'Не найдено казахских слов.'
		]; //тело сообщения об ошибке содержит информацию
		$return_text = json_encode($parseResults, JSON_UNESCAPED_UNICODE);

		$container = [];

		$testMorpher = MorpherTestHelper::createMockMorpher($container, $return_text, 496);

		$lemma = 'test';

		$declensionResult = $testMorpher->qazaq->parse($lemma);
	}

	/**
	 * @throws \Morpher\Ws3Client\Exceptions\RequestsDailyLimit
	 * @throws \Morpher\Ws3Client\Exceptions\TokenRequired
	 * @throws \Morpher\Ws3Client\Exceptions\TokenIncorrectFormat
	 * @throws \Morpher\Ws3Client\Exceptions\InvalidServerResponse
	 * @throws \Morpher\Ws3Client\Exceptions\TokenNotFound
	 * @throws \Morpher\Ws3Client\Exceptions\IpBlocked
	 */
	public function testParse_ExceptionNoS(): void
	{
		$this->expectException(InvalidArgumentEmptyString::class);
		$this->expectExceptionMessage('Передана пустая строка.');

		$parseResults = [
			'code' => 6,
			'message' => 'Не указан обязательный параметр: s.'
		]; //тело сообщения об ошибке содержит информацию
		$return_text = json_encode($parseResults, JSON_UNESCAPED_UNICODE);

		$container = [];

		$testMorpher = MorpherTestHelper::createMockMorpher($container, $return_text, 400);

		$lemma = '+++';

		$declensionResult = $testMorpher->qazaq->parse($lemma);
	}

	/**
	 * @throws \Morpher\Ws3Client\Exceptions\RequestsDailyLimit
	 * @throws \Morpher\Ws3Client\Exceptions\TokenRequired
	 * @throws \Morpher\Ws3Client\Exceptions\TokenIncorrectFormat
	 * @throws \Morpher\Ws3Client\Exceptions\InvalidServerResponse
	 * @throws \Morpher\Ws3Client\Exceptions\TokenNotFound
	 * @throws \Morpher\Ws3Client\Exceptions\IpBlocked
	 */
	public function testParse_ExceptionNoS2(): void
	{
		$this->expectException(InvalidArgumentEmptyString::class);
		$this->expectExceptionMessage('Передана пустая строка.');

		$parseResults = [
			'code' => 6,
			'message' => 'Другое сообщение от сервера'
		]; //с кодом 6 любое сообшение от сервера не учитывается
		$return_text = json_encode($parseResults, JSON_UNESCAPED_UNICODE);

		$container = [];

		$testMorpher = MorpherTestHelper::createMockMorpher($container, $return_text, 400);

		$lemma = '+++';

		$declensionResult = $testMorpher->qazaq->parse($lemma);
	}
}
