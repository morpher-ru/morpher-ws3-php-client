<?php declare(strict_types=1);
require_once __DIR__."/../../vendor/autoload.php";
require_once __DIR__."/../../src/Morpher.php";
require_once __DIR__."/../../src/WebClient.php";
require_once __DIR__."/../../src/russian/Gender.php";
//require_once __DIR__."/../MorpherTestHelper.php";
require_once __DIR__."/../../src/exceptions/MorpherError.php";
require_once __DIR__."/../../src/exceptions/IpBlocked.php";
require_once __DIR__."/../../src/exceptions/TokenNotFound.php";
require_once __DIR__."/../../src/exceptions/TokenIncorrectFormat.php";
require_once __DIR__."/../../src/russian/exceptions/InvalidFlags.php";
require_once __DIR__."/../../src/russian/exceptions/DeclensionNotSupportedUseSpell.php";
require_once __DIR__."/../../src/exceptions/InvalidArgumentEmptyString.php";
require_once __DIR__."/../../src/exceptions/InvalidServerResponse.php";
require_once __DIR__."/../../src/russian/exceptions/RussianWordsNotFound.php";
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