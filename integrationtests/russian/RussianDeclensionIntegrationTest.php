<?php declare(strict_types=1);
require_once __DIR__."/../../vendor/autoload.php";
require_once __DIR__."/../../src/Morpher.php";
require_once __DIR__."/../../src/WebClient.php";
require_once __DIR__."/../../src/russian/Gender.php";
//require_once __DIR__."/../MorpherTestHelper.php";
require_once __DIR__."/../../src/exceptions/MorpherError.php";
require_once __DIR__."/../../src/exceptions/IpBlocked.php";
require_once __DIR__."/../../src/russian/exceptions/InvalidFlags.php";
require_once __DIR__."/../../src/russian/exceptions/DeclensionNotSupportedUseSpell.php";
require_once __DIR__."/../../src/exceptions/InvalidArgumentEmptyString.php";
require_once __DIR__."/../../src/exceptions/InvalidServerResponse.php";
require_once __DIR__."/../../src/russian/exceptions/RussianWordsNotFound.php";
require_once __DIR__."/../IntegrationBase.php";
use PHPUnit\Framework\TestCase;

use Morpher\Ws3Client\WebClient;
use Morpher\Ws3Client\Morpher;

//use Morpher\Ws3Client\Morpher;
use Morpher\Ws3Client\Russian as Russian;



final class RussianDeclensionIntegrationTest extends IntegrationBase
{
    /*
    private static string $token='';
    private static $base_url;
    private static $webClient;
    private static $testMorpher;

    public static function setUpBeforeClass(): void
    {
        $token='';
        foreach (getenv() as $settingKey => $settingValue) {
            if ($settingKey=='MORPHER_RU_TOKEN')
            {
                $token=$settingValue;
                break;
            }
        }

        //$token="YThkYWI1ZmUtN2E0Ny00YzE3LTg0ZWEtNDZmYWNiN2QxOWZl";        

        //if (empty($token)) throw new Exception('Secret token not found or empty. Tests will not run.');
        if (empty($token)) $token="YThkYWI1ZmUtN2E0Ny00YzE3LTg0ZWEtNDZmYWNiN2QxOWZl";    

        self::$token=$token;

        self::$base_url = 'https://ws3.morpher.ru';
        self::$webClient=new WebClient(self::$base_url,self::$token);
        self::$testMorpher=new Morpher(self::$webClient);        

    }
*/
    // public function testSimple(): void
    // {
    //     $testMorpher=self::$testMorpher;
        
    //     $rus_dec=$testMorpher->russian->Parse('Соединенное королевство');
    //     //$rus_dec=$testMorpher->russian->Parse('+++');
    //     print_r($rus_dec);
    //     $this->assertTrue(true);    
    // }

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
        
        $this->assertEquals("о тесте", $declensionResult->PrepositionalWithO);

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
        $this->assertEquals("о тестах", $declensionResult->Plural->PrepositionalWithO);

        $this->assertEquals(Russian\Gender::Masculine, $declensionResult->Gender);

    }


    public function testSplitFio_Success(): void
    {

        $lemma="Александр Пушкин Сергеевич";

         
        $declensionResult=self::$testMorpher->russian->Parse($lemma);

        $this->assertInstanceOf(Russian\DeclensionForms::class ,$declensionResult);
        //Assert.IsNotNull($declensionResult);
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
    
        $declensionResult=self::$testMorpher->russian->Parse($lemma);

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
    
        $declensionResult=self::$testMorpher->russian->Parse($lemma);

    }


    public function testParse_ExceptionNoS(): void
    {
        $this->expectException(\Morpher\Ws3Client\InvalidArgumentEmptyString::class);
        $this->expectExceptionMessage('Передана пустая строка.');

    
        $lemma='';
    
        $declensionResult=self::$testMorpher->russian->Parse($lemma);

    }


    public function testParse_DeclensionNotSupportedUseSpell(): void
    {
        $this->expectException(Russian\DeclensionNotSupportedUseSpell::class);
        $this->expectExceptionMessage('Склонение числительных в declension не поддерживается. Используйте метод spell.');
   
        $lemma='двадцать';
    
        $declensionResult=self::$testMorpher->russian->Parse($lemma);

    }

    public function testParse_InvalidFlags(): void
    {
        $this->expectException(Russian\InvalidFlags::class);
        $this->expectExceptionMessage('Указаны неправильные флаги.');
   
        $lemma='тест';
    
        $declensionResult=self::$testMorpher->russian->Parse($lemma);
    }
}