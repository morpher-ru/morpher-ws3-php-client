<?php declare(strict_types=1);
require_once __DIR__."/../../../vendor/autoload.php";

require_once __DIR__."/../IntegrationBase.php";
use PHPUnit\Framework\TestCase;

use Morpher\Ws3Client\WebClient;
use Morpher\Ws3Client\Morpher;

use Morpher\Ws3Client\Russian as Russian;



final class RussianSpellTest extends IntegrationBase
{
    public function testSpell_Success(): void
    {
    
        
        $spellingResult=self::$testMorpher->russian->Spell(10,'рубль');

        $this->assertInstanceOf(Russian\NumberSpellingResult::class,$spellingResult);
    
        $this->assertNotNull($spellingResult);

        // number
        $this->assertEquals("десять", $spellingResult->NumberDeclension->Nominative);
        $this->assertEquals("десяти", $spellingResult->NumberDeclension->Genitive);
        $this->assertEquals("десяти", $spellingResult->NumberDeclension->Dative);
        $this->assertEquals("десять", $spellingResult->NumberDeclension->Accusative);
        $this->assertEquals("десятью", $spellingResult->NumberDeclension->Instrumental);
        $this->assertEquals("десяти", $spellingResult->NumberDeclension->Prepositional);


        // unit
        $this->assertEquals("рублей", $spellingResult->UnitDeclension->Nominative);
        $this->assertEquals("рублей", $spellingResult->UnitDeclension->Genitive);
        $this->assertEquals("рублям", $spellingResult->UnitDeclension->Dative);
        $this->assertEquals("рублей", $spellingResult->UnitDeclension->Accusative);
        $this->assertEquals("рублями", $spellingResult->UnitDeclension->Instrumental);
        $this->assertEquals("рублях", $spellingResult->UnitDeclension->Prepositional);
    }

    public function testSpell_Empty(): void
    {
        $this->expectException(\Morpher\Ws3Client\InvalidArgumentEmptyString::class);
        
        self::$testMorpher->russian->Spell(1,"   ");
    }


}