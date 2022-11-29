<?php declare(strict_types=1);
require_once __DIR__."/../../../vendor/autoload.php";

require_once __DIR__."/../IntegrationBase.php";
use PHPUnit\Framework\TestCase;

use Morpher\Ws3Client\WebClient;
use Morpher\Ws3Client\Morpher;

use Morpher\Ws3Client\Russian as Russian;


final class RussianSpellOrdinalTest extends IntegrationBase
{
    public function testSpellOrdinal_Success(): void
    {
        $spellingResult=self::$testMorpher->russian->SpellOrdinal(7518 ,'колесо');

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

    public function testSpell_Empty(): void
    {
        $this->expectException(\Morpher\Ws3Client\InvalidArgumentEmptyString::class);
        
        self::$testMorpher->russian->SpellOrdinal(1,"   ");
    }
}
