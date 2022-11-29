<?php
declare(strict_types=1);
require_once __DIR__ . "/../../../vendor/autoload.php";

require_once __DIR__ . "/../MorpherTestHelper.php";

use PHPUnit\Framework\TestCase;

use Morpher\Ws3Client\Ukrainian as Ukrainian;

final class UkrainianSpellTest extends TestCase
{
    public function testSpell_Success(): void
    {
        $parseResults = [
            "n" => [
                "Н" => "десять",
                "Р" => "десяти",
                "Д" => "десяти",
                "З" => "десять",
                "О" => "десятьма",
                "М" => "десяти",
                "К" => "десять"
            ],
            "unit" => [
                "Н" => "рублів",
                "Р" => "рублів",
                "Д" => "рублям",
                "З" => "рублів",
                "О" => "рублями",
                "М" => "рублях",
                "К" => "рублів"
            ]
        ];

        $return_text = json_encode($parseResults, JSON_UNESCAPED_UNICODE);

        $unit = "рубль";

        $container = [];

        $testMorpher = MorpherTestHelper::createMockMorpher($container, $return_text);

        $spellingResult = $testMorpher->ukrainian->Spell(10, $unit);

        $transaction = reset($container);//get first element of requests history

        //check request parameters, headers, uri
        $request = $transaction['request'];

        $uri = $request->getUri();
        $this->assertEquals('/ukrainian/spell', $uri->getPath());
        $this->assertEquals('test.uu', $uri->getHost());
        $this->assertEquals('n=10&unit=' . rawurlencode($unit), $uri->getQuery());

        $this->assertInstanceOf(Ukrainian\NumberSpellingResult::class, $spellingResult);

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

        $container = [];

        $testMorpher = MorpherTestHelper::createMockMorpher($container, '');

        $declensionResult = $testMorpher->ukrainian->Spell(1, '   ');
    }
}
