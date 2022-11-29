<?php
declare(strict_types=1);
require_once __DIR__ . "/../../../vendor/autoload.php";

require_once __DIR__ . "/../MorpherTestHelper.php";

use PHPUnit\Framework\TestCase;

use Morpher\Ws3Client\WebClient;

//use Morpher\Ws3Client\Morpher;
use Morpher\Ws3Client\Russian as Russian;

final class RussianSpellDateTest extends TestCase
{
    public function SpellDateProvider(): array
    {
        return [["2019-06-29"], [new \DateTime('29.06.2019')], [(new \DateTime('29.06.2019'))->getTimestamp()]];
    }

    /**
     * @dataProvider  SpellDateProvider
     */
    public function testSpellDate_Success($date): void
    {
        $parseResults = [
            "И" => "двадцать девятое июня две тысячи девятнадцатого года",
            "Р" => "двадцать девятого июня две тысячи девятнадцатого года",
            "Д" => "двадцать девятому июня две тысячи девятнадцатого года",
            "В" => "двадцать девятое июня две тысячи девятнадцатого года",
            "Т" => "двадцать девятым июня две тысячи девятнадцатого года",
            "П" => "двадцать девятом июня две тысячи девятнадцатого года"
        ];

        $return_text = json_encode($parseResults, JSON_UNESCAPED_UNICODE);

        $container = [];

        $testMorpher = MorpherTestHelper::createMockMorpher($container, $return_text);

        $dateSpellingResult = $testMorpher->russian->SpellDate($date);

        $this->assertInstanceOf(Russian\DateSpellingResult::class, $dateSpellingResult);

        $this->assertEquals("двадцать девятое июня две тысячи девятнадцатого года", $dateSpellingResult->Nominative);
        $this->assertEquals("двадцать девятого июня две тысячи девятнадцатого года", $dateSpellingResult->Genitive);
        $this->assertEquals("двадцать девятому июня две тысячи девятнадцатого года", $dateSpellingResult->Dative);
        $this->assertEquals("двадцать девятое июня две тысячи девятнадцатого года", $dateSpellingResult->Accusative);
        $this->assertEquals("двадцать девятым июня две тысячи девятнадцатого года", $dateSpellingResult->Instrumental);
        $this->assertEquals("двадцать девятом июня две тысячи девятнадцатого года", $dateSpellingResult->Prepositional);
    }

    public function testSpellDate_Empty(): void
    {
        $this->expectException(\Morpher\Ws3Client\InvalidArgumentEmptyString::class);

        $container = [];

        $testMorpher = MorpherTestHelper::createMockMorpher($container, '{}');

        $declensionResult = $testMorpher->russian->SpellDate('   ');
    }

    public function testSpellDate_Exception(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $container = [];
        $testMorpher = MorpherTestHelper::createMockMorpher($container, '{}');
        $testMorpher->russian->SpellDate(null);
    }

    public function testSpellDate_IncorrectFormat(): void
    {
        $this->expectException(Russian\IncorrectDateFormat::class);
        $this->expectExceptionMessage("Дата указана в некорректном формате.");
        $container = [];
        $testMorpher = MorpherTestHelper::createMockMorpher($container,
            '{"code":8,"message":"Дата указана в некорректном формате."}', 499);
        $testMorpher->russian->SpellDate("2022.10.01");
    }
}
