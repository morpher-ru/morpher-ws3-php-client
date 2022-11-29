<?php declare(strict_types=1);
require_once __DIR__."/../../../vendor/autoload.php";

require_once __DIR__."/../IntegrationBase.php";
use PHPUnit\Framework\TestCase;

use Morpher\Ws3Client\WebClient;
use Morpher\Ws3Client\Morpher;

use Morpher\Ws3Client\Ukrainian as Ukrainian;


final class UkrainianDeclensionTest extends IntegrationBase
{
    public function testParse_Success(): void
    {
        $lemma='тест';

        $declensionResult=self::$testMorpher->ukrainian->Parse($lemma);

        $this->assertInstanceOf(Ukrainian\DeclensionResult::class,$declensionResult);

        $this->assertEquals("тест", $declensionResult->Nominative);
        $this->assertEquals("теста", $declensionResult->Genitive);
        $this->assertEquals("тесту", $declensionResult->Dative);
        $this->assertEquals("тест", $declensionResult->Accusative);
        $this->assertEquals("тестом", $declensionResult->Instrumental);
        $this->assertEquals("тесті", $declensionResult->Prepositional);
        $this->assertEquals("тесте", $declensionResult->Vocative);

        $this->assertEquals(Ukrainian\Gender::Masculine, $declensionResult->Gender);
    }

    public function testParse_ExceptionNoWords(): void
    {
        $this->expectException(Ukrainian\UkrainianWordsNotFound::class);
        $this->expectExceptionMessage('Не найдено украинских слов.');
   
        $lemma='test';
        self::$testMorpher->ukrainian->Parse($lemma);
    }

    public function testParse_InvalidArgumentEmptyString(): void
    {
        $this->expectException(\Morpher\Ws3Client\InvalidArgumentEmptyString::class);
        $this->expectExceptionMessage('Передана пустая строка.');

        $lemma='';
        self::$testMorpher->ukrainian->Parse($lemma);
    }

    public function testParse_InvalidFlags(): void
    {
        $this->expectException(Ukrainian\InvalidFlags::class);
        $this->expectExceptionMessage('Указаны неправильные флаги.');
   
        $lemma='тест';
        self::$testMorpher->ukrainian->Parse($lemma,["AAA","BBB"]);
    }
}