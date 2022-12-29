<?php declare(strict_types = 1);
require_once __DIR__."/../../../vendor/autoload.php";

require_once __DIR__."/../MorpherTestHelper.php";

use Morpher\Ws3Client\russian\RussianWordsNotFound;
use PHPUnit\Framework\TestCase;

use Morpher\Ws3Client\Russian as Russian;



final class RussianSpellOrdinalTest extends TestCase
{

    public function testSpellOrdinal_Success(): void
    {
        $parseResults = [            
                "n" => [
                  "И" => "семь тысяч пятьсот восемнадцатое",
                  "Р" => "семь тысяч пятьсот восемнадцатого",
                  "Д" => "семь тысяч пятьсот восемнадцатому",
                  "В" => "семь тысяч пятьсот восемнадцатое",
                  "Т" => "семь тысяч пятьсот восемнадцатым",
                  "П" => "семь тысяч пятьсот восемнадцатом"
                ],
                "unit" => [
                  "И" => "колесо",
                  "Р" => "колеса",
                  "Д" => "колесу",
                  "В" => "колесо",
                  "Т" => "колесом",
                  "П" => "колесе"
                ]         
        ];

        $return_text = json_encode($parseResults,JSON_UNESCAPED_UNICODE);

        $unit = "колесо";
        $num = 7518;

        $container = [];

        $testMorpher = MorpherTestHelper::createMockMorpher($container,$return_text);
        
        $spellingResult = $testMorpher->russian->spellOrdinal($num,$unit);

        $transaction = reset($container);//get first element of requests history

        //check request parameters, headers, uri
        $request = $transaction['request'];        

        $uri = $request->getUri();
        $this->assertEquals('/russian/spell-ordinal',$uri->getPath());
        $this->assertEquals('test.uu',$uri->getHost());
        $this->assertEquals('n='.$num.'&unit='.rawurlencode($unit),$uri->getQuery());

        $this->assertInstanceOf(Russian\NumberSpellingResult::class,$spellingResult);
   
        $this->assertNotNull($spellingResult);

        // number
        $this->assertEquals("семь тысяч пятьсот восемнадцатое", $spellingResult->NumberDeclension->Nominative);
        $this->assertEquals("семь тысяч пятьсот восемнадцатого", $spellingResult->NumberDeclension->Genitive);
        $this->assertEquals("семь тысяч пятьсот восемнадцатому", $spellingResult->NumberDeclension->Dative);
        $this->assertEquals("семь тысяч пятьсот восемнадцатое", $spellingResult->NumberDeclension->Accusative);
        $this->assertEquals("семь тысяч пятьсот восемнадцатым", $spellingResult->NumberDeclension->Instrumental);
        $this->assertEquals("семь тысяч пятьсот восемнадцатом", $spellingResult->NumberDeclension->Prepositional);


        // unit
        $this->assertEquals("колесо", $spellingResult->UnitDeclension->Nominative);
        $this->assertEquals("колеса", $spellingResult->UnitDeclension->Genitive);
        $this->assertEquals("колесу", $spellingResult->UnitDeclension->Dative);
        $this->assertEquals("колесо", $spellingResult->UnitDeclension->Accusative);
        $this->assertEquals("колесом", $spellingResult->UnitDeclension->Instrumental);
        $this->assertEquals("колесе", $spellingResult->UnitDeclension->Prepositional);
    }

    public function testSpellOrdinal_Empty(): void
    {
        $this->expectException(\Morpher\Ws3Client\InvalidArgumentEmptyString::class);
        $this->expectExceptionMessage("empty");

        $container = [];

        $responseJson = '{"code": 6, "message": "empty"}';

        $testMorpher = MorpherTestHelper::createMockMorpher($container, $responseJson, 400);

        $declensionResult = $testMorpher->russian->spellOrdinal(1,'   ');
    }

    public function testSpellOrdinal_NoRussianWords(): void
    {
        $this->expectException(RussianWordsNotFound::class);
        $this->expectExceptionMessage("empty");

        $container = [];

        $responseJson = '{"code": 5, "message": "empty"}';

        $testMorpher = MorpherTestHelper::createMockMorpher($container, $responseJson, 496);

        $declensionResult = $testMorpher->russian->spellOrdinal(1,'   ');
    }
}
