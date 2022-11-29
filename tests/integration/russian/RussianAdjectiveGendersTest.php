<?php declare(strict_types=1);
require_once __DIR__."/../../../vendor/autoload.php";

require_once __DIR__."/../IntegrationBase.php";
use PHPUnit\Framework\TestCase;

use Morpher\Ws3Client\WebClient;
use Morpher\Ws3Client\Morpher;

use Morpher\Ws3Client\Russian as Russian;


final class RussianAdjectiveGendersTest extends IntegrationBase
{
    public function testAdjectiveGenders_Success(): void
    {
        $adjectiveGenders=self::$testMorpher->russian->AdjectiveGenders("уважаемый");

        $this->assertNotNull($adjectiveGenders);
        $this->assertInstanceOf(Russian\AdjectiveGenders::class,$adjectiveGenders);

        $this->assertEquals("уважаемая", $adjectiveGenders->Feminine);
        $this->assertEquals("уважаемое", $adjectiveGenders->Neuter);
        $this->assertEquals("уважаемые", $adjectiveGenders->Plural);   
    }

    public function testAdjectiveGenders_error(): void
    {
        $this->expectException(Russian\AdjectiveFormIncorrect::class);
        $adjectiveGenders=self::$testMorpher->russian->AdjectiveGenders("уважаемого");

 /*       $this->assertNotNull($adjectiveGenders);
        $this->assertInstanceOf(Russian\AdjectiveGenders::class,$adjectiveGenders);

         $this->assertEquals("ERROR", $adjectiveGenders->Feminine);
        $this->assertEquals("ERROR", $adjectiveGenders->Neuter);
        $this->assertEquals("ERROR", $adjectiveGenders->Plural);    */
    }

    public function testAdjectiveGenders_Empty(): void
    {
        $this->expectException(\Morpher\Ws3Client\InvalidArgumentEmptyString::class);
        
        self::$testMorpher->russian->AdjectiveGenders("   ");
    }
}
