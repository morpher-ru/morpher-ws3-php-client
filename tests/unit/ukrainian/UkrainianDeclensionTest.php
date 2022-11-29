<?php
declare(strict_types=1);
require_once __DIR__ . "/../../../vendor/autoload.php";

require_once __DIR__ . "/../MorpherTestHelper.php";

use Morpher\Ws3Client\InvalidArgumentEmptyString;
use PHPUnit\Framework\TestCase;

use Morpher\Ws3Client\Ukrainian as Ukrainian;

final class UkrainianDeclensionTest extends TestCase
{
    public function testFlags(): void
    {
        $parseResults = [];

        $return_text = json_encode($parseResults, JSON_UNESCAPED_UNICODE);

        $lemma = 'тест';

        $container = [];

        $testMorpher = MorpherTestHelper::createMockMorpher($container, $return_text);

        $declensionResult = $testMorpher->ukrainian->Parse($lemma, ['flagA', 'flagB', 'flagC']);

        $transaction = reset($container);//get first element of requests history

        //check request parameters, headers, uri
        $request = $transaction['request'];

        $uri = $request->getUri();
        $this->assertEquals('/ukrainian/declension', $uri->getPath());
        $this->assertEquals('test.uu', $uri->getHost());
        $this->assertEquals('s=' . rawurlencode($lemma) . '&flags=' . rawurlencode('flagA,flagB,flagC'),
            $uri->getQuery());
    }

    public function testParse_Success(): void
    {
        $parseResults = [
            "Р" => "теста",
            "Д" => "тесту",
            "З" => "теста",
            "О" => "тестом",
            "М" => "тесті",
            "К" => "тесте",
            "рід" => "Чоловічий"
        ];

        $return_text = json_encode($parseResults, JSON_UNESCAPED_UNICODE);

        $lemma = 'тест';

        $container = [];

        $testMorpher = MorpherTestHelper::createMockMorpher($container, $return_text);

        $declensionResult = $testMorpher->ukrainian->Parse($lemma);

        $transaction = reset($container);//get first element of requests history

        //check request parameters, headers, uri
        $request = $transaction['request'];

        $uri = $request->getUri();
        $this->assertEquals('/ukrainian/declension', $uri->getPath());
        $this->assertEquals('test.uu', $uri->getHost());
        $this->assertEquals('s=' . rawurlencode($lemma), $uri->getQuery());

        $this->assertInstanceOf(Ukrainian\DeclensionResult::class, $declensionResult);

        $this->assertEquals("тест", $declensionResult->Nominative);
        $this->assertEquals("теста", $declensionResult->Genitive);
        $this->assertEquals("тесту", $declensionResult->Dative);
        $this->assertEquals("теста", $declensionResult->Accusative);
        $this->assertEquals("тестом", $declensionResult->Instrumental);
        $this->assertEquals("тесті", $declensionResult->Prepositional);
        $this->assertEquals("тесте", $declensionResult->Vocative);

        $this->assertEquals(Ukrainian\Gender::Masculine, $declensionResult->Gender);
    }

    public function testParse_ExceptionNoWords(): void
    {
        $this->expectException(Ukrainian\UkrainianWordsNotFound::class);
        $this->expectExceptionMessage('Не найдено украинских слов.');

        $parseResults = [
            'code' => 5,
            'message' => 'Не найдено украинских слов.'
        ];
        $return_text = json_encode($parseResults, JSON_UNESCAPED_UNICODE);

        $container = [];

        $testMorpher = MorpherTestHelper::createMockMorpher($container, $return_text, 496);

        $lemma = 'test';

        $declensionResult = $testMorpher->ukrainian->Parse($lemma);
    }

    public function testParse_ExceptionNoS(): void
    {
        $this->expectException(\Morpher\Ws3Client\InvalidArgumentEmptyString::class);
        $this->expectExceptionMessage('Передана пустая строка.');

        $parseResults = [
            'code' => 6,
            'message' => 'Не указан обязательный параметр: s.'
        ];
        $return_text = json_encode($parseResults, JSON_UNESCAPED_UNICODE);

        $container = [];

        $testMorpher = MorpherTestHelper::createMockMorpher($container, $return_text, 400);

        $lemma = '+++';

        $declensionResult = $testMorpher->ukrainian->Parse($lemma);
    }

    public function testParse_ExceptionNoS2(): void
    {
        $this->expectException(\Morpher\Ws3Client\InvalidArgumentEmptyString::class);
        $this->expectExceptionMessage('Передана пустая строка.');

        $parseResults = [
            'code' => 6,
            'message' => 'Другое сообщение от сервера'
        ]; //с кодом 6 любое сообшение от сервера не учитывается
        $return_text = json_encode($parseResults, JSON_UNESCAPED_UNICODE);

        $container = [];

        $testMorpher = MorpherTestHelper::createMockMorpher($container, $return_text, 400);

        $lemma = '+++';

        $declensionResult = $testMorpher->ukrainian->Parse($lemma);
    }

    public function testParse_InvalidFlags(): void
    {
        $this->expectException(Ukrainian\InvalidFlags::class);
        $this->expectExceptionMessage('Указаны неправильные флаги.');

        $parseResults = [
            'code' => 12,
            'message' => 'Указаны неправильные флаги.'
        ];
        $return_text = json_encode($parseResults, JSON_UNESCAPED_UNICODE);

        $container = [];

        $testMorpher = MorpherTestHelper::createMockMorpher($container, $return_text, 494);

        $lemma = 'двадцать';

        $declensionResult = $testMorpher->ukrainian->Parse($lemma, ["AAA", "BBB"]);
    }
}
