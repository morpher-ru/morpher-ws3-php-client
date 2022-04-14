<?php declare(strict_types=1);
require_once __DIR__."/../../vendor/autoload.php";

require_once __DIR__."/../IntegrationBase.php";

use PHPUnit\Framework\TestCase;

use Morpher\Ws3Client\WebClient;
use Morpher\Ws3Client\Morpher;

//use Morpher\Ws3Client\Morpher;
use Morpher\Ws3Client\Russian as Russian;



final class RussianDeclensionIntegration2Test extends TestCase
{


    public function testTokenIncorrectFormatError(): void
    {
        $token='23525555555555555555555555555555555555555555555555';// incorrect format token, valid length

 
        $token=$token;


        $webClient=new WebClient(IntegrationBase::BASE_URL,$token);
        $testMorpher=new Morpher($webClient);     

        $this->expectException(\Morpher\Ws3Client\TokenIncorrectFormat::class);

        $lemma='тест';
        $declensionResult=$testMorpher->russian->Parse($lemma);

    }

    public function testTokenNotFoundError(): void
    {
        $token='41e2111a-767b-4a07-79A3-d52c02cb5a0d';// not existing token, valid length
 
        $token=$token;

        $webClient=new WebClient(IntegrationBase::BASE_URL,$token);
        $testMorpher=new Morpher($webClient);     

        $this->expectException(\Morpher\Ws3Client\TokenNotFound::class);

        $lemma='тест';
        $declensionResult=$testMorpher->russian->Parse($lemma);

    }

}