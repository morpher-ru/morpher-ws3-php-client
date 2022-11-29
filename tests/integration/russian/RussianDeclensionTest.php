<?php declare(strict_types=1);
require_once __DIR__."/../../../vendor/autoload.php";

require_once __DIR__."/../IntegrationBase.php";
use PHPUnit\Framework\TestCase;

use Morpher\Ws3Client\WebClient;
use Morpher\Ws3Client\Morpher;

use Morpher\Ws3Client\Russian as Russian;


final class RussianDeclensionTest extends IntegrationBase
{
    public function testParse_Success(): void
    {
        $lemma='тест';

        $declensionResult=self::$testMorpher->russian->Parse($lemma);

        $this->assertInstanceOf(Russian\DeclensionResult::class,$declensionResult);

        $this->assertEquals("тест", $declensionResult->Nominative);
        $this->assertEquals("теста", $declensionResult->Genitive);
        $this->assertEquals("тесту", $declensionResult->Dative);
        $this->assertEquals("тест", $declensionResult->Accusative);
        $this->assertEquals("тестом", $declensionResult->Instrumental);
        $this->assertEquals("тесте", $declensionResult->Prepositional);
        
        $this->assertEquals("о тесте", $declensionResult->PrepositionalWithO);//не работает с токеном от бесплатной версии

        $this->assertEquals("в тесте", $declensionResult->Where);
        $this->assertEquals("в тест", $declensionResult->To);
        $this->assertEquals("из теста", $declensionResult->From);

        $this->assertInstanceOf(Russian\DeclensionForms::class,$declensionResult->Plural);

        $this->assertEquals("тесты", $declensionResult->Plural->Nominative);
        $this->assertEquals("тестов", $declensionResult->Plural->Genitive);
        $this->assertEquals("тестам", $declensionResult->Plural->Dative);
        $this->assertEquals("тесты", $declensionResult->Plural->Accusative);
        $this->assertEquals("тестами", $declensionResult->Plural->Instrumental);
        $this->assertEquals("тестах", $declensionResult->Plural->Prepositional);
        $this->assertEquals("о тестах", $declensionResult->Plural->PrepositionalWithO); //не работает с токеном от бесплатной версии

        $this->assertEquals(Russian\Gender::Masculine, $declensionResult->Gender);
    }

    public function testSplitFio_Success(): void
    {
        $lemma="Пушкин Александр Сергеевич";

        $declensionResult=self::$testMorpher->russian->Parse($lemma, [Russian\Flags::Name]);

        $this->assertInstanceOf(Russian\DeclensionForms::class, $declensionResult);
        $this->assertNotNull($declensionResult->FullName);
        $this->assertInstanceOf(Russian\FullName::class ,$declensionResult->FullName);        
        $this->assertEquals("Пушкин", $declensionResult->FullName->Surname);
        $this->assertEquals("Александр", $declensionResult->FullName->Name);
        $this->assertEquals("Сергеевич", $declensionResult->FullName->Pantronymic);
    }

    public function testParse_Exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
    
        $lemma='';
    
        self::$testMorpher->russian->Parse($lemma);
    }


    public function testNullGenitive(): void
    {
        $lemma='теля';
   
        $genitive = self::$testMorpher->russian->Parse($lemma)->Genitive;

        $this->assertNull($genitive);
    }


    public function testParse_ExceptionNoWords(): void
    {
        $this->expectException(Russian\RussianWordsNotFound::class);
        $this->expectExceptionMessage('Не найдено русских слов.');
   
        $lemma='test';
        self::$testMorpher->russian->Parse($lemma);
    }

    public function testParse_ExceptionNoS(): void
    {
        $this->expectException(\Morpher\Ws3Client\InvalidArgumentEmptyString::class);
        $this->expectExceptionMessage('Передана пустая строка.');

        $lemma='';
        self::$testMorpher->russian->Parse($lemma);
    }

    public function testParse_DeclensionNotSupportedUseSpell(): void
    {
        $this->expectException(Russian\DeclensionNotSupportedUseSpell::class);
        $this->expectExceptionMessage('Склонение числительных в declension не поддерживается. Используйте метод spell.');
   
        $lemma='двадцать';
        self::$testMorpher->russian->Parse($lemma);
    }

    public function testParse_InvalidFlags(): void
    {
        $this->expectException(Russian\InvalidFlags::class);
        $this->expectExceptionMessage('Указаны неправильные флаги.');
   
        $lemma='тест';
        self::$testMorpher->russian->Parse($lemma,["AAA","BBB"]);
    }
}
