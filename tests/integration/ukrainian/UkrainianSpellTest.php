<?php declare(strict_types=1);
require_once __DIR__."/../../../vendor/autoload.php";

require_once __DIR__."/../IntegrationBase.php";
use PHPUnit\Framework\TestCase;

use Morpher\Ws3Client\WebClient;
use Morpher\Ws3Client\Morpher;


use Morpher\Ws3Client\Ukrainian as Ukrainian;


final class UkrainianSpellTest extends IntegrationBase
{
    public function testSpell_Success(): void
    {
        $spellingResult=self::$testMorpher->ukrainian->Spell(10,'рубль');

        $this->assertInstanceOf(Ukrainian\NumberSpellingResult::class,$spellingResult);
    
        $this->assertNotNull($spellingResult);

        // number
        $this->assertEquals("десять", $spellingResult->NumberDeclension->Nominative);
        $this->assertEquals("десяти", $spellingResult->NumberDeclension->Genitive);
        $this->assertEquals("десяти", $spellingResult->NumberDeclension->Dative);
        $this->assertEquals("десять", $spellingResult->NumberDeclension->Accusative);
        $this->assertEquals("десятьма", $spellingResult->NumberDeclension->Instrumental);
        $this->assertEquals("десяти", $spellingResult->NumberDeclension->Prepositional);
        $this->assertEquals("десять", $spellingResult->NumberDeclension->Vocative);

        // unit
        $this->assertEquals("рублів", $spellingResult->UnitDeclension->Nominative);
        $this->assertEquals("рублів", $spellingResult->UnitDeclension->Genitive);
        $this->assertEquals("рублям", $spellingResult->UnitDeclension->Dative);
        $this->assertEquals("рублів", $spellingResult->UnitDeclension->Accusative);
        $this->assertEquals("рублями", $spellingResult->UnitDeclension->Instrumental);
        $this->assertEquals("рублях", $spellingResult->UnitDeclension->Prepositional);
        $this->assertEquals("рублів", $spellingResult->UnitDeclension->Vocative);
    }

    public function testSpell_Empty(): void
    {
        $this->expectException(\Morpher\Ws3Client\InvalidArgumentEmptyString::class);
        
        self::$testMorpher->ukrainian->Spell(1,"   ");
    }
}