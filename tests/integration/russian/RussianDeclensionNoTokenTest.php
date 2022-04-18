<?php declare(strict_types=1);
require_once __DIR__."/../../../vendor/autoload.php";

require_once __DIR__."/../IntegrationBase.php";
use PHPUnit\Framework\TestCase;

use Morpher\Ws3Client\WebClient;
use Morpher\Ws3Client\Morpher;

use Morpher\Ws3Client\Russian as Russian;



final class RussianDeclensionNoTokenTest extends TestCase
{
    static function getToken(): string
    {
        return '';//NO TOKEN
    }

    static WebClient $webClient;
    static Morpher $testMorpher;

    public static function setUpBeforeClass(): void
    {
        $token=self::getToken();



        self::$webClient=new WebClient(IntegrationBase::BASE_URL,$token);
        self::$testMorpher=new Morpher(self::$webClient);        
    }

    public function testParse_SuccessNoToken(): void
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
        
        $this->assertNull($declensionResult->PrepositionalWithO);//не работает с токеном от бесплатной версии

        $this->assertNull($declensionResult->Where);
        $this->assertNull($declensionResult->To);
        $this->assertNull($declensionResult->From);

        $this->assertInstanceOf(Russian\DeclensionForms::class,$declensionResult->Plural);

        $this->assertEquals("тесты", $declensionResult->Plural->Nominative);
        $this->assertEquals("тестов", $declensionResult->Plural->Genitive);
        $this->assertEquals("тестам", $declensionResult->Plural->Dative);
        $this->assertEquals("тесты", $declensionResult->Plural->Accusative);
        $this->assertEquals("тестами", $declensionResult->Plural->Instrumental);
        $this->assertEquals("тестах", $declensionResult->Plural->Prepositional);
        $this->assertNull($declensionResult->Plural->PrepositionalWithO); //не работает с токеном от бесплатной версии

        $this->assertNull($declensionResult->Gender);
    }
}