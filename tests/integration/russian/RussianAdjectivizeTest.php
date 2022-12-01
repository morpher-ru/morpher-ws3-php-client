<?php declare(strict_types = 1);
require_once __DIR__."/../../../vendor/autoload.php";

require_once __DIR__."/../IntegrationBase.php";
use PHPUnit\Framework\TestCase;

use Morpher\Ws3Client\WebClient;
use Morpher\Ws3Client\Morpher;

use Morpher\Ws3Client\Russian as Russian;


final class RussianAdjectivizeTest extends IntegrationBase
{
    public function testAdjectivize_Success(): void
    {
        $list = self::$testMorpher->russian->Adjectivize("мытыщи");

        $this->assertNotNull($list);
        $this->assertIsArray($list);
        $this->assertCount(2,$list);

        $this->assertContains("мытыщинский",$list);
        $this->assertContains("мытыщенский", $list);
    }

    public function testAdjectiveGenders_Empty(): void
    {
        $this->expectException(\Morpher\Ws3Client\InvalidArgumentEmptyString::class);
        
        self::$testMorpher->russian->Adjectivize("   ");
    }
}
